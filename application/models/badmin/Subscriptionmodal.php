<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Subscriptionmodal extends CI_Model {
    function fnGetInfo($VarData, $VarStatus, $VarId = '',$VarCompanyId='') {
        $this->db->select('se.*,DATE_FORMAT(se.reqdatetime,\'%d-%m-%Y\ %H:%i %p\') as reqdatetime,DATE_FORMAT(se.status_updated_datetime,\'%d-%m-%Y\ %H:%i %p\') as status_updated_datetime,u.contactname as request_raised_by,bau.contactname as status_updatedby')->from(KN_SUBSCRIBERENQUIRY . ' AS se');
        $this->db->join(KN_USERS . ' AS u', 'u.id = se.mrkt_dept_userid','LEFT');
        $this->db->join(KN_USERS . ' AS bau', 'bau.id = se.badmin_userid','LEFT');
        $this->db->join(KN_SUBSCRIBERLIST . ' AS sl', 'sl.subscriber_id = se.id','LEFT');
        if ($VarData <> '') {
            $ArrWhere['companyname'] = trim($VarData);
        }
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarId <> '') {
            $ArrWhere['sl.subscriber_id'] = $VarId;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
       // echo $this->db->get_compiled_select();
        return $this->db->get()->result();
    }
    function fnCount($Varcmpny,$Varcity,$Varemailid,$Varmobno,$Varfromdate,$Vartodate) {
        $ArrWhere = array();
        if ($Varcmpny <> '') {
            $ArrWhere[] = "pi.companyname='" . trim($Varcmpny). "'";
        }if($Varfromdate != '' && $Vartodate != '')
        {
            $startDate = $this->changeReverseDate($Varfromdate);
            $endDate = $this->changeReverseDate($Vartodate);
            $startDate = $startDate;
            $endDate = $endDate;
            
             $ArrWhere[] = "sl.subscriber_enddate >='" . trim($startDate). "'";
             $ArrWhere[] = "sl.subscriber_enddate <='" . trim($endDate). "'";
        }
        else if($Varfromdate != '')
        {
            $startDate = $this->changeReverseDate($Varfromdate);
            $startDate = $startDate;
            
             $ArrWhere[] = "DATE_FORMAT(sl.subscriber_enddate,\"%Y-%m-%d\") ='" . trim($startDate) . "'";
        }
        else if($Vartodate != '')
        {
              $endDate = $this->changeReverseDate($Vartodate);
              $endDate = $endDate;
              $ArrWhere[] = "DATE_FORMAT(sl.subscriber_enddate,\"%Y-%m-%d\") ='" . trim($endDate) . "'";
        }
        
        if ($Varcity <> '') {
            $ArrWhere[] = "pi.city ='" . trim($Varcity) . "'";
        }
        if ($Varemailid <> '') {
            $ArrWhere[] = "pi.email_id='" . trim($Varemailid) . "'";
        }
        if ($Varmobno <> '') {
            $ArrWhere[] = "pi.mobile_no='" . trim($Varmobno). "'";
        } 
        
        
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
       // $ArrWhere[] = "se.requeststatus=2";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = ' WHERE ' . implode(" and ", $ArrWhere);
        }
        
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_SUBSCRIBERLIST . " AS sl LEFT JOIN  ".KN_SUBSCRIBERENQUIRY." As se ON sl.subscriber_id=se.id LEFT JOIN  ".KN_PROFORMAINVOICE." As pi ON sl.proforma_id=pi.id
                        JOIN (
                            SELECT subscriber_id, MAX(id) AS max_id
                            FROM tbl_subscriber_list
                            GROUP BY subscriber_id
                            ) AS latest 
                        ON sl.subscriber_id = latest.subscriber_id AND sl.id = latest.max_id " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnList($Varcmpny,$Varcity,$Varemailid,$Varmobno,$Varfromdate,$Vartodate,$VarLimit = 11, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('pi.companyname', 'sl.status');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'sl.id';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($Varcmpny <> '') {
            $ArrWhere[] = "pi.companyname='" . trim($Varcmpny). "'";
        }if($Varfromdate != '' && $Vartodate != '')
        {
            $startDate = $this->changeReverseDate($Varfromdate);
            $endDate = $this->changeReverseDate($Vartodate);
            $startDate = $startDate;
            $endDate = $endDate;
            
             $ArrWhere[] = "sl.subscriber_enddate >='" . trim($startDate). "'";
             $ArrWhere[] = "sl.subscriber_enddate <='" . trim($endDate). "'";
        }
        else if($Varfromdate != '')
        {
            $startDate = $this->changeReverseDate($Varfromdate);
            $startDate = $startDate;
            
             $ArrWhere[] = "DATE_FORMAT(sl.subscriber_enddate,\"%Y-%m-%d\") ='" . trim($startDate) . "'";
        }
        else if($Vartodate != '')
        {
              $endDate = $this->changeReverseDate($Vartodate);
              $endDate = $endDate;
              $ArrWhere[] = "DATE_FORMAT(sl.subscriber_enddate,\"%Y-%m-%d\") ='" . trim($endDate) . "'";
        }
        
        if ($Varcity <> '') {
            $ArrWhere[] = "pi.city ='" . trim($Varcity) . "'";
        }
        if ($Varemailid <> '') {
            $ArrWhere[] = "pi.email_id='" . trim($Varemailid) . "'";
        }
        if ($Varmobno <> '') {
            $ArrWhere[] = "pi.mobile_no='" . trim($Varmobno). "'";
        } 
        
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        //$ArrWhere[] = "se.requeststatus = 2 AND pi.invoice_status=1";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = ' WHERE ' . implode(" and ", $ArrWhere);
        }
        
         $VarSqlLab = "SELECT sl.*,pi.companyname,pi.city,pi.email_id,pi.mobile_no,se.subscription_category,pi.package_id,DATE_FORMAT(sl.subscriber_startdate,\"%d/%m/%Y\") as startdate,DATE_FORMAT(sl.subscriber_enddate,\"%d/%m/%Y\") as enddate
                      FROM " . KN_SUBSCRIBERLIST . " AS sl LEFT JOIN  ".KN_SUBSCRIBERENQUIRY." As se ON sl.subscriber_id=se.id LEFT JOIN  ".KN_PROFORMAINVOICE." As pi ON sl.proforma_id=pi.id 
                        JOIN (
                                SELECT subscriber_id, MAX(id) AS max_id
                                FROM tbl_subscriber_list
                                GROUP BY subscriber_id
                            ) AS latest 
                        ON sl.subscriber_id = latest.subscriber_id AND sl.id = latest.max_id" . $VarWhere . "  order by " . $VarSortBy . " " . $VarSortOrder;
                    $ObjResult = $this->db->query($VarSqlLab);
                    return $ObjResult;
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
        if ($this->db->update(KN_SUBSCRIBERLIST, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
    function fnGetInvoiceInfo($VarData, $VarStatus, $VarId = '',$VarCompanyId='',$VarSubscriberId = '') {
        $this->db->select('se.*,sl.*,pi.id as pid,pi.invoiceno,pi.confirm_status,DATE_FORMAT(pi.invoicegen_date,\'%d-%m-%Y\') as invoicegen_date,pi.subscriber_id,pi.proforma_type,pi.status as proforma_status,pi.additional_users as invo_additional_users,pi.data_storage_limit as invo_data_storage_limit,pi.file_storage_limit as invo_file_storage_limit,pi.mobile_no as invomobile_no,pi.remarks as invoremarks,pi.email_id as invoemail_id,pi.designation as invodesignation,pi.contactperson as invocontactperson,pi.businesstype as invobusinesstype,pi.package_id as invopackageid,pi.purchasetype as invopurchasetype,pi.companyname as invocmpnyname,pi.address as invoaddress,pi.city as invocity,pi.state as invostate,pi.pincode as invopincode,pi.gst_no as invogst_no,pi.IECODE as ie_code,pi.country as invocountry,pi.invoice_refno,pi.invoice_type,pi.save_status,pi.paymentstatus,pi.payment_from,pi.transaction_no,DATE_FORMAT(pi.transaction_date,\'%d-%m-%Y\')as transaction_date,pi.payment_mode,pi.transaction_value,pi.invoice_validity,pi.terms_and_condition,pi.total,pi.subtotal,pi.cgst_amount,pi.igst_amount,pi.sgst_amount,pi.subscription_period,DATE_FORMAT(pi.invoice_datetime,\'%d-%m-%Y\') as invoice_datetime,DATE_FORMAT(se.reqdatetime,\'%d-%m-%Y\ %H:%i %p\') as reqdatetime,DATE_FORMAT(se.status_updated_datetime,\'%d-%m-%Y\ %H:%i %p\') as status_updated_datetime,DATE_FORMAT(sl.subscriber_startdate,\'%d-%m-%Y\') as subscrpstartdate,DATE_FORMAT(sl.subscriber_enddate,\'%d-%m-%Y\') as subscrpenddate,u.contactname as request_raised_by,bau.contactname as status_updatedby')->from(KN_SUBSCRIBERENQUIRY . ' AS se');
        $this->db->join(KN_USERS . ' AS u', 'u.id = se.mrkt_dept_userid','LEFT');
        $this->db->join(KN_USERS . ' AS bau', 'bau.id = se.badmin_userid','LEFT');
        $this->db->join(KN_PROFORMAINVOICE . ' AS pi', 'pi.subscriber_id = se.id','LEFT');
        $this->db->join(KN_SUBSCRIBERLIST . ' AS sl', 'sl.subscriber_id = pi.subscriber_id','LEFT');
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
        if ($VarSubscriberId <> '') {
            $ArrWhere['pi.subscriber_id'] = $VarSubscriberId;
        }
        if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }
        
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
       // echo $this->db->get_compiled_select();
        return $this->db->get()->result();
    }
    function fndetCount($VarsubscriberId) {
        $ArrWhere = array();
        if ($VarsubscriberId <> '') {
            $ArrWhere[] = "se.id=" . "'$VarsubscriberId'";
        }
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
       // $ArrWhere[] = "se.requeststatus=2";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
        $VarSqlLab = "SELECT count(1) as trec FROM " . KN_SUBSCRIBERENQUIRY . " AS se LEFT JOIN  ".KN_PROFORMAINVOICE." As pi ON se.id=pi.subscriber_id LEFT JOIN ".KN_SUBSCRIBERLIST." As sl ON sl.subscriber_id = pi.subscriber_id LEFT JOIN ".KN_USERS." As u ON u.id = se.mrkt_dept_userid LEFT JOIN ".KN_USERS." As bau ON bau.id = se.badmin_userid " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fndetList($VarsubscriberId,$proforma_type,$VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
       
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
        $VarSortCols = array('se.companyname', 'sl.status');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'pi.id';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
          
        if ($VarsubscriberId <> '') {
            $ArrWhere[] = "se.id=" . "'$VarsubscriberId'";
        }
        if ($proforma_type <> '') {
            $ArrWhere[] = "pi.proforma_type=" . "'$proforma_type'";
        }
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $ArrWhere[] = "pi.paymentstatus <> 3";
        $ArrWhere[] = "pi.invoice_status =2";
        //$ArrWhere[] = "se.requeststatus = 2 AND pi.invoice_status=1";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
         $VarSqlLab = "SELECT se.*,sl.*,
        pi.id as pid,
        pi.subscriber_id,
        pi.invoiceno,
        DATE_FORMAT(pi.invoice_datetime,'%d-%m-%Y') as invoice_date,
        pi.purchasetype as invopurchasetype,
        pi.companyname as invocmpnyname,
        pi.address as invoaddress,
        pi.city as invocity,
        pi.state as invostate,
        pi.pincode as invopincode,
        pi.gst_no as invogst_no,
        pi.country as invocountry,
        pi.invoice_refno,pi.invoice_type,
        pi.save_status,
        pi.paymentstatus,
        pi.payment_from,
        pi.transaction_no,
        pi.subtotal as profinvval,
        DATE_FORMAT(pi.transaction_date,'%d-%m-%Y')as transaction_date,
        pi.payment_mode,
        pi.transaction_value,
        pi.invoice_validity,
        pi.terms_and_condition,
        pi.subtotal,pi.cgst_amount,
        pi.igst_amount,pi.sgst_amount,
        pi.subscription_period,
        pi.status as proforma_status,
        DATE_FORMAT(pi.invoice_datetime,'%d-%m-%Y') as invoice_datetime,
        DATE_FORMAT(se.reqdatetime,'%d-%m-%Y %H:%i %p') as reqdatetime,
        DATE_FORMAT(se.status_updated_datetime,'%d-%m-%Y %H:%i %p') as status_updated_datetime,
        u.contactname as request_raised_by,
        bau.contactname as status_updatedby
                      FROM " . KN_SUBSCRIBERENQUIRY . " AS se LEFT JOIN  ".KN_PROFORMAINVOICE." As pi ON se.id=pi.subscriber_id LEFT JOIN ".KN_SUBSCRIBERLIST." As sl ON sl.subscriber_id = pi.subscriber_id LEFT JOIN ".KN_USERS." As u ON u.id = se.mrkt_dept_userid LEFT JOIN ".KN_USERS." As bau ON bau.id = se.badmin_userid " . $VarWhere . " GROUP BY pi.id,sl.subscriber_id" ." order by " . $VarSortBy . " " . $VarSortOrder;
                    $ObjResult = $this->db->query($VarSqlLab);
                    return $ObjResult;
    }
    public function getPackagedetails($subscriberId,$proforma_id)
    {
        $ArrStatus = unserialize(ARRSTATUS);
        $subscription_category = unserialize(ARRSUBSCCATEGORY);
        $ArrPackage = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_PACKAGE  . ' AS p', 'p.id,p.description', array('p.status' => '1'),3);
        $subscription_period = unserialize(ARRSUBSCRIPTIONPERIOD);
        $purchase_type = unserialize(ARRPURCHASETYPE);
        $data_storage_limit = unserialize(ARRFILESTORAGE);
        $file_storage_limit = unserialize(ARRFILESTORAGE);
        
        $status=[];
        $category=[];
        $purchasetype=[];
        $subscriptionperiod=[];
        $datastorage_limit =[];
        $filestorage_limit =[];
        
        foreach($ArrStatus as $key=>$value){
           
               $status[]=array('id' => $key, 'name' => $value);
           
        }
        foreach($subscription_category as $key=>$value){
           
               $category[]=array('id' => $key, 'name' => $value);
           
        }
        foreach($purchase_type as $key=>$value){
           
               $purchasetype[]=array('id' => $key, 'name' => $value);
           
        }
        foreach($data_storage_limit as $key=>$value){
           
               $datastorage_limit[]=array('id' => $key, 'name' => $value);
           
        }
        foreach($file_storage_limit as $key=>$value){
           
               $filestorage_limit[]=array('id' => $key, 'name' => $value);
           
        }foreach($subscription_period as $key=>$value){
           
               $subscriptionperiod[]=array('id' => $key, 'name' => $value);
           
        }
        $results = [
            'column' => [
                ['title' => 'Subscription Category', 'width' => '9%','type' => 'dropdown','source'=>$category, 'align' => 'left'],
                ['title' => 'Package Details', 'width' => '8%','align' => 'left'],
                ['title' => 'No. of Users (Package)', 'width' => '9%', 'align' => 'center'],
                ['title' => 'Data Storage' . PHP_EOL . 'Limit (Package)', 'width' => '9%',  'align' => 'center'],
                ['title' => 'File Storage' . PHP_EOL . 'Limit (Package)', 'width' => '9%',  'align' => 'center'],
                ['title' => 'No. of Add. Users (Chargeable)', 'width' => '9%',  'align' => 'center'],
                ['title' => 'Add. Data Storage Limit (Chargeable)', 'width' => '9%','type' => 'dropdown','source'=>$datastorage_limit,  'align' => 'center'],
                ['title' => 'Add. File Storage Limit (Chargeable)' , 'width' => '9%', 'type' => 'dropdown','source'=>$filestorage_limit, 'align' => 'center'],
                ['title' => 'Purchase Type', 'width' => '8%', 'type' => 'dropdown','source'=>$purchasetype,  'align' => 'left'],
                ['title' => 'Subscription   Period', 'width' => '8%', 'type' => 'dropdown','source'=>$subscriptionperiod,   'align' => 'left'],
                ['title' => 'Subscription'. PHP_EOL .'Start Date', 'width' => '8%',  'align' => 'left'],
                ['title' => 'Subscription'. PHP_EOL .'End Date', 'width' => '8%',  'align' => 'left'],
                ['title' => 'Subscripton Status', 'type' => 'dropdown','source'=>$status,   'width' => '6%',  'align' => 'left']
            ],
            'data'   => $this->getPackagedetailsData($subscriberId,$proforma_id)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    function getPackagedetailsData($subscriberId,$proforma_id)
    {
       
        $query  = "SELECT   se.subscription_category,
                            mp.description as package,
                            pi.package_id, /* here from subscriberinfo package_id change to proformainvoice pckg_id */
                            mp.no_of_users,
                            mp.data_limit,
                            mp.file_limit,
                            pi.additional_users,
                            pi.data_storage_limit,
                            pi.file_storage_limit,
                            pi.purchasetype,
                            pi.subscription_period,
                            IF(pi.invoiceno<>'',DATE_FORMAT(sl.subscriber_startdate,\"%d-%m-%Y\"),'') as subscriber_startdate,
                            IF(pi.invoiceno<>'',DATE_FORMAT(sl.subscriber_enddate,\"%d-%m-%Y\"),'') as subscriber_enddate,
                            sl.status /*-- Already se.status shown now changed to sl.status--*/
                     FROM " . KN_PROFORMAINVOICE . " AS pi
                     LEFT JOIN " . KN_SUBSCRIBERENQUIRY . " AS se ON se.id=pi.subscriber_id
                     LEFT JOIN " . KN_SUBSCRIBERLIST . " AS sl ON sl.proforma_id=pi.id
                     LEFT JOIN " . KN_MASTER_PACKAGE . " AS mp ON mp.id=pi.package_id 
                     WHERE pi.proforma_type='NPI' and pi.id='$proforma_id' and se.id = " . $subscriberId;
        $data   = $this->db->query($query)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['subscription_category'], $value['package'],  $value['no_of_users'],$value['data_limit'], $value['file_limit'], $value['additional_users'],$value['data_storage_limit'],$value['file_storage_limit'],$value['purchasetype'],$value['subscription_period'],$value['subscriber_startdate'],$value['subscriber_enddate'],$value['status']];
        }
        
        return $result;
    }

    function getPackagedetailsData_usage_limit($subscriberId,$proforma_id)
    {
        $result = [];
       
        $query  = "SELECT   se.subscription_category,
                            mp.description as package,
                            pi.package_id, /* here from subscriberinfo package_id change to proformainvoice pckg_id */
                            mp.no_of_users,
                            mp.data_limit,
                            mp.file_limit,
                            pi.additional_users,
                            pi.data_storage_limit,
                            pi.file_storage_limit,
                            pi.purchasetype,
                            pi.subscription_period,
                            IF(pi.invoiceno<>'',DATE_FORMAT(sl.subscriber_startdate,\"%d-%m-%Y\"),'') as subscriber_startdate,
                            IF(pi.invoiceno<>'',DATE_FORMAT(sl.subscriber_enddate,\"%d-%m-%Y\"),'') as subscriber_enddate,
                            sl.status /*-- Already se.status shown now changed to sl.status--*/
                     FROM " . KN_PROFORMAINVOICE . " AS pi
                     LEFT JOIN " . KN_SUBSCRIBERENQUIRY . " AS se ON se.id=pi.subscriber_id
                     LEFT JOIN " . KN_SUBSCRIBERLIST . " AS sl ON sl.proforma_id=pi.id
                     LEFT JOIN " . KN_MASTER_PACKAGE . " AS mp ON mp.id=pi.package_id 
                     WHERE pi.proforma_type='NPI' and pi.id='$proforma_id' and se.id = " . $subscriberId;
        $result   = $this->db->query($query)->result_array();
       
        // foreach ($data as $key => $value)
        // {
        //     $result[$key] = [$value['subscription_category'], $value['package'],  $value['no_of_users'],$value['data_limit'], $value['file_limit'], $value['additional_users'],$value['data_storage_limit'],$value['file_storage_limit'],$value['purchasetype'],$value['subscription_period'],$value['subscriber_startdate'],$value['subscriber_enddate'],$value['status']];
        // }
        
        return $result;
    }
     public function getPackagewiseuserdetails($subscriberId,$proforma_id)
    {
       
       
        $ArrUserType = unserialize(ARRUSERTYPE);

       

      
        $UserType =[];
        
        foreach($ArrUserType as $key=>$value){
           
               $UserType[]=array('id' => $key, 'name' => $value);
           
        }
        $UserType = array_filter($UserType, function($user) {
                    return $user['id'] != 1; // Exclude 'User Admin' with id 1
        });
        $UserType = array_values($UserType); // Reindex array if necessary
        
        $results = [
            'column' => [
                ['title' => 'User Department', 'width' => '25%','type' => 'dropdown','source'=>$UserType, 'align' => 'left'],
                ['title' => 'No. of Users Allowed' . PHP_EOL . ' Per Department (Package)', 'width' => '25%','align' => 'right'],
                ['title' => 'No. of Add. Users Allowed' . PHP_EOL . ' Per Department (Chargeable)', 'width' => '25%', 'align' => 'right'],
                ['title' => 'Total No. of Users' . PHP_EOL . ' Allowed Per Department','readOnly' => true,'width' => '25%',  'align' => 'right'],
               // ['title' => 'Users Department Wise Roles Allowed ' . PHP_EOL . 'Limit (Package)','type' => 'html','width' => '20%','width' => '20%',  'align' => 'center']
            ],
            'data'   => $this->getPackagewiseuserdetailsData($subscriberId,$proforma_id)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    function getPackagewiseuserdetailsData($subscriberId,$proforma_id)
    {
        
        $query  = "SELECT * FROM " . KN_MNGUSERDEPTCNT . " AS duc  WHERE duc.subscriber_id=$subscriberId and duc.proforma_id=$proforma_id and duc.status=1 order by duc.id asc";
        $data   = $this->db->query($query)->result_array();
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['usertype'], $value['dept_usercount'],$value['additional_users'],$value['total_users']];
        }
        
        return $result;
    }
    public function savedeptcount($data, $subscriber_id, $proforma_id, $edit_status)
    {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->userid = $ArrUserLoggedInfo['id'];
        date_default_timezone_set('Asia/Kolkata');
    
        try {
            // Fetch all existing usertypes for the given subscriber and proforma
            $query = "SELECT * FROM " . KN_MNGUSERDEPTCNT . " WHERE subscriber_id = ? AND proforma_id = ?";
            $existingRecords = $this->db->query($query, [$subscriber_id, $proforma_id])->result_array();
    
            // Extract the existing usertypes from the database
            $existingUsertypes = array_column($existingRecords, 'usertype', 'id');
    
            // Extract posted usertypes from the input data
            $postedUsertypes = array_column($data, 0);
    
            // Find usertypes that are in the database but not in the posted data (mark as inactive)
            foreach ($existingUsertypes as $id => $dbUsertype) {
                if (!in_array($dbUsertype, $postedUsertypes)) {
                    // Mark these records as inactive
                    $this->db->update(KN_MNGUSERDEPTCNT, 
                        ['status' => 2, 'updatedby' => $this->userid, 'dateupdated' => date('Y-m-d H:i:s')], 
                        ['id' => $id]
                    );
                }
            }
    
            // Process posted data
            foreach ($data as $key => $value) {
                $usertype = $value[0];
                $dept_usercount = $value[1];
                $additional_users = $value[2];
                $total_users = $value[3];
    
                // Check if the usertype exists in the database
                $query = "SELECT * FROM " . KN_MNGUSERDEPTCNT . " WHERE subscriber_id = ? AND proforma_id = ? AND usertype = ?";
                $existingRecord = $this->db->query($query, [$subscriber_id, $proforma_id, $usertype])->row_array();
    
                if ($existingRecord) {
                    // If it exists, check its status
                    if ($existingRecord['status'] == 2) {
                        // Reactivate the record by setting status to 1
                        $this->db->update(KN_MNGUSERDEPTCNT, 
                            [
                                'dept_usercount' => $dept_usercount,
                                'additional_users' => $additional_users,
                                'total_users' => $total_users,
                                'status' => 1, // Reactivate
                                'updatedby' => $this->userid,
                                'dateupdated' => date('Y-m-d H:i:s')
                            ], 
                            ['id' => $existingRecord['id']]
                        );
                    } else {
                        // Otherwise, simply update the existing record
                        $this->db->update(KN_MNGUSERDEPTCNT, 
                            [
                                'dept_usercount' => $dept_usercount,
                                'additional_users' => $additional_users,
                                'total_users' => $total_users,
                                'updatedby' => $this->userid,
                                'dateupdated' => date('Y-m-d H:i:s')
                            ], 
                            ['id' => $existingRecord['id']]
                        );
                    }
                } else {
                    // If the usertype is not found, insert a new record
                    $insertData = [
                        'subscriber_id' => $subscriber_id,
                        'proforma_id' => $proforma_id,
                        'usertype' => $usertype,
                        'dept_usercount' => $dept_usercount,
                        'additional_users' => $additional_users,
                        'total_users' => $total_users,
                        'status' => 1, // Set the status to active
                        'updatedby' => $this->userid,
                        'datecreated' => date('Y-m-d H:i:s'),
                        'dateupdated' => date('Y-m-d H:i:s')
                    ];
                    $this->db->insert(KN_MNGUSERDEPTCNT, $insertData);
                }
            }
    
            // Update the subscriber list to indicate the operation is complete
            $this->db->update(KN_SUBSCRIBERLIST, 
                ['pckg_saved_status' => 1], 
                ['subscriber_id' => $subscriber_id, 'proforma_id' => $proforma_id]
            );
    
            return ['errcode' => 1, 'msg' => 'Operation successful'];
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return ['errcode' => -1, 'msg' => 'An error occurred during processing'];
        }
    }
    
    
    
    function fnDeptList($Varsubscriberid,$Varproformaid,$VarLimit = 10, $offset = 0, $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'asc') ? 'desc' : 'asc';
        $VarSortCols = array('ud.id');
        $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'ud.id';
        $VarLimitInfo = $VarLimit;
        if ($offset >= 1) {
            $VarLimitInfo = $offset . "," . $VarLimit;
        }
        $ArrWhere = array();
        if ($Varsubscriberid <> '') {
            $ArrWhere[] = "ud.subscriber_id=" . "'$Varsubscriberid'";
        }
        if ($Varproformaid <> '') {
            $ArrWhere[] = "ud.proforma_id=" . "'$Varproformaid'";
        }
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
        $VarSqlLab = "SELECT ud.* FROM " . KN_MNGUSERDEPTCNT . " AS ud " . $VarWhere . " order by " . $VarSortBy . " " . $VarSortOrder;
        $ObjResult = $this->db->query($VarSqlLab);
        return $ObjResult;
    }
    function fnDeptCount($Varsubscriberid,$Varproformaid) {
        $ArrWhere = array();
        if ($Varsubscriberid <> '') {
            $ArrWhere[] = "ud.subscriber_id=" . "'$Varsubscriberid'";
        }
        if ($Varproformaid <> '') {
            $ArrWhere[] = "ud.proforma_id=" . "'$Varproformaid'";
        }
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MNGUSERDEPTCNT . " AS ud " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    function fnChangeDeptStat($checkboxids, $optvalue) {
        $Arrids = json_decode($checkboxids, true);
        $this->db->where_in('id', $Arrids);
        if ($this->db->update(KN_MNGUSERDEPTCNT, array('status' => $optvalue))) {
            return true;
        }
        return false;
    }
    public function getdeptdetails($subscriberId,$proforma_id,$dept_id)
    {
        $ARRENABLE = unserialize(ARRENABLE);
        $status=[];
       
        foreach($ARRENABLE as $key=>$value){
           
               $status[]=array('id' => $key, 'name' => $value);
           
        }
        $results = [
            'column' => [
                ['title' => 'List Name', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request Type', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request' . PHP_EOL . 'Enable/Disable', 'width' => '20%', 'type' => 'dropdown','source'=>$status, 'align' => 'center']
            ],
            'data'   => $this->getdeptdetailsData($subscriberId,$proforma_id,$dept_id)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    function getdeptdetailsData($subscriberId,$proforma_id,$dept_id)
    {
        $query  = "SELECT * FROM " . KN_DEPTROLE_PERMISSION . " AS dp WHERE dp.usertype=$dept_id AND dp.proforma_id =$proforma_id AND dp.subscriber_id = " . $subscriberId;
        $dataset   = $this->db->query($query)->result_array();
        
        if(!empty($dataset) && count($dataset)>0){
           $result = [];
            foreach ($dataset as $key => $value)
            {
                $result[$key] = [$value['title'], $value['type'],  $value['status']];
            } 
        }else{
            $data   = array (array("Queue List","CAD Q.A. Request",""));
            $result = [];
            foreach ($data as $key => $value)
            {
                $result[$key] = [$value[0], $value[1],  ''];
            }  
        }
        return $result;
    }
    public function getmerchdeptdetails($subscriberId,$proforma_id,$dept_id)
    {
        $ARRENABLE = unserialize(ARRENABLE);
        $status=[];
       
        foreach($ARRENABLE as $key=>$value){
           
               $status[]=array('id' => $key, 'name' => $value);
           
        }
        $results = [
            'column' => [
                ['title' => 'List Name', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request Type', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request' . PHP_EOL . 'Enable/Disable', 'width' => '20%', 'type' => 'dropdown','source'=>$status, 'align' => 'center']
            ],
            'data'   => $this->getmerchdeptdetailsData($subscriberId,$proforma_id,$dept_id)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    function getmerchdeptdetailsData($subscriberId,$proforma_id,$dept_id)
    {
       
        $query  = "SELECT * FROM " . KN_DEPTROLE_PERMISSION . " AS dp WHERE dp.usertype=$dept_id AND dp.proforma_id =$proforma_id AND dp.subscriber_id = " . $subscriberId;
        $dataset   = $this->db->query($query)->result_array();
        
        if(!empty($dataset) && count($dataset)>0){
           $result = [];
            foreach ($dataset as $key => $value)
            {
                $result[$key] = [$value['title'], $value['type'],  $value['status']];
            } 
        }else{
        $data   = array (
          array("WIP List","CAD Request","Enable"),
          array("WIP List","Sample Request","Enable"),
          array("WIP List","Embellishment Request","Enable"),
          array("WIP List","Bom (Art-1) Request","Enable"),
          array("WIP List","Bom (Art-2) Request","Enable"),
          array("WIP List","Fabric Request","Enable"),
          array("WIP List","Production Request","Enable"),
          array("WIP List","Doc. & Log. Request","Enable")
        );
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value[0], $value[1],  ''];
        }
        }
        return $result;
    }
    public function getmngdeptdetails($subscriberId,$proforma_id,$dept_id)
    {
        $ARRENABLE = unserialize(ARRENABLE);
        $status=[];
       
        foreach($ARRENABLE as $key=>$value){
           
               $status[]=array('id' => $key, 'name' => $value);
           
        }
        $results = [
            'column' => [
                ['title' => 'List Name', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request Type', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request' . PHP_EOL . 'Enable/Disable', 'width' => '20%', 'type' => 'dropdown','source'=>$status, 'align' => 'center']
            ],
            'data'   => $this->getmngdeptdetailsData($subscriberId,$proforma_id,$dept_id)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    function getmngdeptdetailsData($subscriberId,$proforma_id,$dept_id)
    {
        $query  = "SELECT * FROM " . KN_DEPTROLE_PERMISSION . " AS dp WHERE dp.usertype=$dept_id AND dp.proforma_id =$proforma_id AND dp.subscriber_id = " . $subscriberId;
        $dataset   = $this->db->query($query)->result_array();
        
        if(!empty($dataset) && count($dataset)>0){
           $result = [];
            foreach ($dataset as $key => $value)
            {
                $result[$key] = [$value['title'], $value['type'],  $value['status']];
            } 
        }else{
            $data   = array (
              array("Enquiry Authorization List","Authorization Status","Enable"),
              array("Work Authorization List","All Request Authorization","Enable"),
              array("M.I. List","All M.I. Authorization List","Enable"),
              array("P.I. Approval List","All P.I. Approval List","Enable"),
              array("P.I. List","All P.I. Payment Approval List","Enable")
            );
            $result = [];
            foreach ($data as $key => $value)
            {
                $result[$key] = [$value[0], $value[1],  ''];
            } 
        }
        return $result;
    }
    public function getsampledetails($subscriberId,$proforma_id,$dept_id)
    {
        $ARRENABLE = unserialize(ARRENABLE);
        $status=[];
       
        foreach($ARRENABLE as $key=>$value){
           
               $status[]=array('id' => $key, 'name' => $value);
           
        }
        $results = [
            'column' => [
                ['title' => 'List Name', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request Type', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request' . PHP_EOL . 'Enable/Disable', 'width' => '20%', 'type' => 'dropdown','source'=>$status, 'align' => 'center']
            ],
            'data'   => $this->getsampledetailsData($subscriberId,$proforma_id,$dept_id)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    function getsampledetailsData($subscriberId,$proforma_id,$dept_id)
    {   
        $query  = "SELECT * FROM " . KN_DEPTROLE_PERMISSION . " AS dp WHERE dp.usertype=$dept_id AND dp.proforma_id=$proforma_id AND dp.subscriber_id = " . $subscriberId;
        $dataset   = $this->db->query($query)->result_array();
        
        if(!empty($dataset) && count($dataset)>0){
           $result = [];
            foreach ($dataset as $key => $value)
            {
                $result[$key] = [$value['title'], $value['type'],  $value['status']];
            } 
        }else{
            $data   = array (
            array("Queue List","Sample Q.A. Request","Enable")
            );
            $result = [];
            foreach ($data as $key => $value)
            {
                $result[$key] = [$value[0], $value[1],  ''];
            }
        }
        return $result;
    }
    public function getpurchasedetails($subscriberId,$proforma_id,$dept_id)
    {
        $ARRENABLE = unserialize(ARRENABLE);
        $status=[];
       
        foreach($ARRENABLE as $key=>$value){
           
               $status[]=array('id' => $key, 'name' => $value);
           
        }
        $results = [
            'column' => [
                ['title' => 'List Name', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request Type', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request' . PHP_EOL . 'Enable/Disable', 'width' => '20%', 'type' => 'dropdown','source'=>$status, 'align' => 'center']
            ],
            'data'   => $this->getpurchasedetailsData($subscriberId,$proforma_id,$dept_id)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    function getpurchasedetailsData($subscriberId,$proforma_id,$dept_id)
    {   
        $query  = "SELECT * FROM " . KN_DEPTROLE_PERMISSION . " AS dp WHERE dp.usertype=$dept_id AND dp.proforma_id=$proforma_id AND dp.subscriber_id = " . $subscriberId;
        $dataset   = $this->db->query($query)->result_array();
        
        if(!empty($dataset) && count($dataset)>0){
           $result = [];
            foreach ($dataset as $key => $value)
            {
                $result[$key] = [$value['title'], $value['type'],  $value['status']];
            } 
        }else{
        $data   = array (
          array("Queue List","P.I. Approval Request","Enable"),
          array("P.I. List","Payment Request","Enable")
        );
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value[0], $value[1],  ''];
        }
        }
        return $result;
    }
    public function getfabricdetails($subscriberId,$proforma_id,$dept_id)
    {
        $ARRENABLE = unserialize(ARRENABLE);
        $status=[];
       
        foreach($ARRENABLE as $key=>$value){
           
               $status[]=array('id' => $key, 'name' => $value);
           
        }
        $results = [
            'column' => [
                ['title' => 'List Name', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request Type', 'width' => '40%','align' => 'left','readOnly' => true],
                ['title' => 'Request' . PHP_EOL . 'Enable/Disable', 'width' => '20%', 'type' => 'dropdown','source'=>$status, 'align' => 'center']
            ],
            'data'   => $this->getfabricdetailsData($subscriberId,$proforma_id,$dept_id)
        ];

        // echo json_encode($results);
        // exit();
        return $results;
    }
    function getfabricdetailsData($subscriberId,$proforma_id,$dept_id)
    {   
        $query  = "SELECT * FROM " . KN_DEPTROLE_PERMISSION . " AS dp WHERE dp.usertype=$dept_id AND dp.proforma_id=$proforma_id AND dp.subscriber_id = " . $subscriberId;
        $dataset   = $this->db->query($query)->result_array();
        
        if(!empty($dataset) && count($dataset)>0){
           $result = [];
            foreach ($dataset as $key => $value)
            {
                $result[$key] = [$value['title'], $value['type'],  $value['status']];
            } 
        }else{
            $data   = array (
              array("Queue List","P.I. Approval Request","Enable"),
              array("P.I. List","Payment Request","Enable")
            );
            $result = [];
            foreach ($data as $key => $value)
            {
                $result[$key] = [$value[0], $value[1],  ''];
            }
        }
        return $result;
    }
    public function fnGetRoleInfo($subscriber_id = '', $proforma_id = '', $dept_id = '',$tablename) {
        
        $this->db->select('GROUP_CONCAT(title) AS title')->from($tablename);
        
        if ($subscriber_id <> '') {
            $ArrWhere['subscriber_id'] = $subscriber_id;
        }
        if ($proforma_id <> '') {
            $ArrWhere['proforma_id'] = $proforma_id;
        }
        if ($dept_id <> '') {
            $ArrWhere['usertype'] = $dept_id;
        }
        $ArrWhere['status'] = 1;
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        $ArrResultList = $this->db->get()->result_array();
        return $ArrResultList;
    }
    
    public function savedeptpermission($data, $subscriber_id, $dept_id, $statuscheck, $proformaid)
{
    $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
    $this->userid = $ArrUserLoggedInfo['id'];
    date_default_timezone_set('Asia/Kolkata');

    $roledeptcnt = $this->fngetDeptCount($subscriber_id, $proformaid, '',1, KN_ROLE_PERMISSION_MASTER);
    $Deptcnt = $this->fngetDeptCount($subscriber_id, $proformaid, '', 1,KN_MNGUSERDEPTCNT);
    $SubscriberAccntExist = $this->fnCheckExsist($subscriber_id, '', $proformaid, KN_MUSERS);

    $primaryId = '';

    // Loop through each data entry and either update or insert as needed
    foreach ($data as $value) {
        $title = $value[0];
        $type = $value[1];
        $status = $value[2];

        // Check if the record already exists
        $existingRecord = $this->db->get_where(KN_DEPTROLE_PERMISSION, [
            'subscriber_id' => $subscriber_id,
            'proforma_id' => $proformaid,
            'usertype' => $dept_id,
            'title' => $title,
            'type' => $type
        ])->row_array();

        if ($existingRecord) {
            // Update the existing record
            $this->db->update(KN_DEPTROLE_PERMISSION, [
                'status' => $status,
                'dateupdated' => date('Y-m-d H:i:s')
            ], [
                'subscriber_id' => $subscriber_id,
                'proforma_id' => $proformaid,
                'usertype' => $dept_id,
                'title' => $title,
                'type' => $type
            ]);
            $primaryId = $existingRecord['id']; // Assume there is an `id` column
        } else {
            // Insert a new record
            $this->db->insert(KN_DEPTROLE_PERMISSION, [
                'subscriber_id' => $subscriber_id,
                'proforma_id' => $proformaid,
                'usertype' => $dept_id,
                'title' => $title,
                'type' => $type,
                'status' => $status,
                'datecreated' => date('Y-m-d H:i:s'),
                'dateupdated' => date('Y-m-d H:i:s')
            ]);
            $primaryId = $this->db->insert_id();
        }
    }

    // Update dept_saved_status in KN_SUBSCRIBERLIST table
   // $this->updateDeptSavedStatus($subscriber_id, $proformaid, $roledeptcnt, $Deptcnt, $SubscriberAccntExist);

    // Return result based on whether any operation was performed
    if (!empty($primaryId)) {
        $ArrResult['errcode'] = 1;
        $ArrResult['msg'] = 'Valid Input';
    } else {
        $ArrResult['errcode'] = -1;
        $ArrResult['msg'] = 'No Changes Made';
    }

    return $ArrResult;
}

// Helper function for updating dept_saved_status
public function updateDeptSavedStatus($subscriber_id, $proformaid, $roledeptcnt, $Deptcnt, $SubscriberAccntExist)
{
    $status = ($roledeptcnt == $Deptcnt || $SubscriberAccntExist > 0) ? 1 : 2;
    $this->db->update(KN_SUBSCRIBERLIST, ['dept_saved_status' => $status], [
        'subscriber_id' => $subscriber_id,
        'proforma_id' => $proformaid
    ]);
}

    public function fnCheckExsist($subscriberId = '', $deptId = '', $proformaId = '', $tableName) {
    // Sanitize table name to prevent SQL injection
    if (!$this->db->table_exists($tableName)) {
        throw new Exception("Invalid table name: $tableName");
    }
    
    $this->db->from($tableName);
    
    // Dynamically add conditions if parameters are provided
    $conditions = [
        'subscriber_id' => $subscriberId,
        'proforma_id' => $proformaId,
        'usertype' => $deptId,
    ];
    
    foreach ($conditions as $field => $value) {
        if (!empty($value)) {
            $this->db->where_in($field, (array) $value);
        }
    }
    
     // Get the count of matching rows
     $countAll = $this->db->count_all_results();
    
     // Print the generated SQL query
    // echo $this->db->last_query();
     
     return $countAll;
}

    function fngetsubscriptionlist($Varsubscriberid) {
       
        $ArrWhere = array();
        if ($Varsubscriberid <> '') {
            $ArrWhere[] = "sl.subscriber_id=" . "'$Varsubscriberid'";
        }
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
        $VarSqlLab = "SELECT sl.* FROM " . KN_SUBSCRIBERLIST . " AS sl " . $VarWhere;
        $ObjResult = $this->db->query($VarSqlLab)->result_array();
        return $ObjResult;
    }
    public function fnGetsubscriber_LoginInfo($VarLoginId = '', $VarStatus = '',$VarSubscriber_id = '',$VarUsertype = '', $VarUserId = '', $VarCompanyId = '') {
        //$this->db->select('id,contactname,mobile,username,status,desgnid,desgn')->from(KN_MUSERS)->join(KN_USER_DESGN,'desgnid = designationid');
        $this->db->select('*')->from(KN_MUSERS);
        // if ($VarStatus <> '') {
        //     $this->db->where_in('status', array($VarStatus));
        // } else {
        //     $this->db->where_in('status', array(1, 2));
        // }
        if ($VarUserId <> '') {
            $ArrWhere['id'] = $VarUserId;
        }
        if ($VarSubscriber_id <> '') {
            $ArrWhere['subscriber_id'] = $VarSubscriber_id;
        }
        if ($VarUsertype <> '') {
            $ArrWhere['usertype'] = $VarUsertype;
        }
        if ($VarLoginId <> '') {
            $ArrWhere['username'] = trim($VarLoginId);
        }
        /*if ($VarCompanyId <> '') {
            $ArrWhere['companyid'] = $VarCompanyId;
        }*/
        if (!empty($ArrWhere)) {
            $this->db->where($ArrWhere);
        }
        //echo $this->db->get_compiled_select();
        $ArrResultList = $this->db->get()->result();
        return $ArrResultList;
    }
    public function fngetusertype($Var_SubscriberId = '',$Var_DeptId='',$tablename,$Var_Status='') {
        $ArrWhere = array();
        if ($Var_SubscriberId <> '') {
            $ArrWhere[] ="subscriber_id="."'$Var_SubscriberId'";
        }if ($Var_DeptId <> '') {
             $ArrWhere[] ="usertype="."'$Var_DeptId'";
        }if ($Var_Status <> '') {
            $ArrWhere[] ="status="."'$Var_Status'";
       }
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
         $VarSqlLab = "SELECT Distinct usertype FROM " . $tablename . "  " . $VarWhere." order by usertype asc";
        $ObjResult = $this->db->query($VarSqlLab)->result_array();
        return $ObjResult;
    }
    function fnSubuserDeptCount($Vardeptid,$Varsubscriberid,$Varproformaid) {
        $ArrWhere = array();
        if ($Varsubscriberid <> '') {
            $ArrWhere[] = "ud.subscriber_id=" . "'$Varsubscriberid'";
        }
        if ($Varproformaid <> '') {
            $ArrWhere[] = "ud.proforma_id=" . "'$Varproformaid'";
        }
        if ($Vardeptid <> '') {
            $ArrWhere[] = "ud.usertype=" . "'$Vardeptid'";
        }
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
        $VarSqlLab = "SELECT *  FROM " . KN_MNGUSERDEPTCNT . " AS ud " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->total_users;
    }
    function fngetDeptCount($Varsubscriberid,$Varproformaid,$Vardeptid,$Varstatus,$tablename) {
        $ArrWhere = array();
        if ($Varsubscriberid <> '') {
            $ArrWhere[] = "ud.subscriber_id=" . "'$Varsubscriberid'";
        }if ($Varproformaid <> '') {
            $ArrWhere[] = "ud.proforma_id=" . "'$Varproformaid'";
        }
        if ($Vardeptid <> '') {
            $ArrWhere[] = "ud.usertype=" . "'$Vardeptid'";
        }
        if ($Varstatus <> '') {
            $ArrWhere[] = "ud.status=" . "'$Varstatus'";
        }
        $ArrWhere[] = "ud.usertype<>" . "1";
        //$ArrWhere[] = "ud.status=" . "1";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
        $VarSqlLab = "SELECT COUNT(DISTINCT usertype) as utcnt  FROM " . $tablename . " AS ud " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->utcnt;
    }

    function fngetsubscriberuserCount($Varsubscriberid,$Varproformarid) {
        $ArrWhere = array();
        if ($Varsubscriberid <> '') {
            $ArrWhere[] = "ud.subscriber_id=" . "'$Varsubscriberid'";
        }
        if ($Varproformarid <> '') {
            $ArrWhere[] = "ud.proforma_id=" . "'$Varproformarid'";
        }
        $ArrWhere[] = "ud.usertype <> 1";
        $VarWhere = '';
        if (!empty($ArrWhere)) {
            $VarWhere = 'WHERE ' . implode(" and ", $ArrWhere);
        }
        
        $VarSqlLab = "SELECT count(1) as trec  FROM " . KN_MUSERS . " AS ud " . $VarWhere;
        $ObjRows = $this->db->query($VarSqlLab)->row();
        return $ObjRows->trec;
    }
    
    public function fnsubscriberuserList($Varsubscriber_id,$Varproforma_id, $VarLimit = 11,  $VarSortBy, $VarSortOrder) {
        $VarSortOrder = ($VarSortOrder == 'desc') ? 'desc' : 'asc';
       // $VarSortCols = $this->ArrDbCols;
      //  $VarSortBy = (in_array($VarSortBy, $VarSortCols)) ? $VarSortBy : 'dateupdated';
        $VarLimitInfo = $VarLimit;
        // if ($offset >= 1) {
        //     $VarLimitInfo = $offset . "," . $VarLimit;
        // }
        $VarWhere = $this->whereCond('','','','','','','',$Varsubscriber_id,$Varproforma_id);
       
        $VarSql = "SELECT DISTINCT  u.* 
         FROM " . KN_MUSERS . " 
         As u LEFT JOIN " . KN_MNGUSERDEPTCNT ." 
         As udc ON u.subscriber_id = udc.subscriber_id " 
         . $VarWhere . 
         " ORDER BY " . $VarSortBy . " " . $VarSortOrder;
         $ArrObjResult = $this->db->query($VarSql)->result();
      
        // Collect IDs of users who updated records
    $ArrUpdatedbyId = array_column($ArrObjResult, 'updatedby');
    $ArrUpdatedBy = [];

    if (!empty($ArrUpdatedbyId)) {
        $ArrObjUserInfo = $this->commonmodel->getUserInfo('', '', '', $ArrUpdatedbyId);
        if (!empty($ArrObjUserInfo)) {
            foreach ($ArrObjUserInfo as $userObj) {
                $ArrUpdatedBy[$userObj['id']] = $userObj['contactname'];
            }
        }
    }
        return array('listData' => $ArrObjResult, 'updatedByData' => $ArrUpdatedBy);
        //return $this->db->query($VarSql);
    }
    public function whereCond($VarDept = '', $VarUsername='',$VarDesgn='',$VarLoginid = '',$VarEmail_id='',$VarMobno='',$VarStatus='',$varsubscriber_id='',$Varproforma_id='') {
        // newly included u.usertype<>0 for filtering business admin user in user admin login
        $ArrUserType = unserialize(ARRUSERTYPE);
        $coutusertype = count($ArrUserType); 
       if ($VarDept <> '') {
           $ArrWhere[] = "u.usertype= '" . $VarDept . "'";
       }
       if ($VarUsername <> '') {
           $ArrWhere[] = "u.contactname ='" . $VarUsername . "'";   
       }
       if ($VarDesgn <> '') {
           $ArrWhere[] = "u.designation ='" . $VarDesgn . "'"; 
       }
       if ($VarLoginid <> '') {
           $ArrWhere[] = "u.username ='" . $VarLoginid . "'"; 
       }
       if ($VarEmail_id <> '') {
           $ArrWhere[] = "u.email_id = '" . $VarEmail_id . "'";
       }
       if ($VarMobno <> '') {
           $ArrWhere[] = "u.mobile = '" . $VarMobno . "'"; 
       }
       if ($VarStatus <> '') {
           $ArrWhere[] = "u.status=" . $VarStatus;
       } else {
           $ArrWhere[] = "u.status in(1,2) AND u.usertype not in(0,1,$coutusertype)";
       }
       if ($varsubscriber_id <> '') {
           $ArrWhere[] = "u.subscriber_id = '" . $varsubscriber_id . "'"; 
       }else{
           $ArrWhere[] = "u.subscriber_id IS NULL"; 
       }if ($Varproforma_id <> '') {
        $ArrWhere[] = "u.proforma_id = '" . $Varproforma_id . "'"; 
    }else{
        $ArrWhere[] = "u.proforma_id IS NULL"; 
    }
       $VarWhere = " WHERE ". implode(" AND ", $ArrWhere);
       return $VarWhere;
   }
   
}