<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MerchantRequestSentModel extends CI_Model
{
    private $mysqldate;
    private $mysqldatetime;
    public function __construct()
    {
        parent::__construct();
        fnIfCheckUserLoggedIn();
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->subscribid     = $ArrUserLoggedInfo['subscriber_id'];
        $this->mysqldate     = date('Y-m-d');
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->userid        = $ArrUserLoggedInfo['id'];
    }
    
    function getSizeChart($enqId = '')
    {
        $this->db->select('size_ids');
        $ArrRes = $this->db->get_where('tbl_pc_size_chart', array('enquiry_id' => $enqId));
        return $ArrRes->row()->size_ids;
    }
     public function getuserdetail($user_type,$sub_id)
    {
        
        $sql = "SELECT * FROM " . KN_USERS . " as a
                WHERE a.usertype = ".$user_type."  and subscriber_id = " . $sub_id." ";
        $result = $this->db->query($sql)->result_array();
        
        
        return $result;
    }

     public function getsubscribercompanydetail($sub_id)
    {
        
           $sql = "SELECT * FROM " . KN_PROFORMAINVOICE . " as a
                WHERE subscriber_id = " . $sub_id." ";
        $result = $this->db->query($sql)->result_array();
        
        
        return $result;
    }

    function getSizeMaster($size_ids = '')
    {
        $userInfoQry = "SELECT size_name FROM tbl_size_master sm WHERE sm.id IN (" . $size_ids . ")";
        $data = $this->db->query($userInfoQry)->result_array();
        return $data;
    }

    function getSizeMasterDropdown($size_ids = '')
    {
        $userInfoQry = "SELECT id as id, size_name as name FROM tbl_size_master sm WHERE sm.id IN (" . $size_ids . ")";
        $data = $this->db->query($userInfoQry)->result_array();
        $allVar = [ 'id'=> '0', 'name' => 'All' ];
        $allVar1 = [ 'id'=> '00', 'name' => 'Running Size'];
        array_push($data, $allVar);
        array_push($data, $allVar1);
        return $data;
    }

    // ********** Manage All request STARTS HERE *********** /

    public function getManageAllListt_old() {
    // Combine all types (CAD, Sample, BOM) in one query
    $sql = "
        (
            SELECT a.*, c.brandname, b.isriorcode, f.contactname as auth_name, 'CAD' as type_label
            FROM tbl_request as a 
            INNER JOIN kn_order_enquiry as b on a.enquiry_id = b.id 
            INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId = c.id 
            LEFT JOIN ".KN_USERS." as f on a.auth_by = f.id 
            WHERE a.type = 1 AND a.deprt_approval = 0 AND a.subscriberid = ".$this->db->escape($this->subscribid)."
        )
        UNION ALL
        (
            SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, 'Sample' as type_label
            FROM tbl_request as a
            INNER JOIN kn_order_enquiry as b on a.enquiry_id = b.id
            INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId = c.id
            LEFT JOIN ".KN_USERS." as d ON a.auth_by = d.id
            WHERE a.type = 2 AND a.deprt_approval = 0 AND a.draft_status = 1 AND a.subscriberid = ".$this->db->escape($this->subscribid)."
        )
        UNION ALL
        (
            SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, 'BOM' as type_label
            FROM tbl_request as a 
            INNER JOIN kn_order_enquiry as b on a.enquiry_id = b.id 
            INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId = c.id 
            LEFT JOIN ".KN_USERS." as d ON a.auth_by = d.id
            WHERE a.type = 3 AND a.deprt_approval = 0 AND a.draft_status = 0 AND a.subscriberid = ".$this->db->escape($this->subscribid)."
        )
        ORDER BY log DESC;
    ";

    // Execute query to get merged results
    $result = $this->db->query($sql)->result_array();

    // Get CAD, Sample, and BOM requirements
    $cad_sql = "SELECT a.cad_requirement, a.request_id
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id = e.request_id
                WHERE e.type = 1 AND e.deprt_approval = 0 ";
    $cad_data = $this->db->query($cad_sql)->result_array();

    $sam_sql = "SELECT *
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id = e.sample_id
                INNER JOIN tbl_request as b on e.request_id = b.request_id
                WHERE b.type = 2 AND b.deprt_approval = 0 AND a.req_sent_status = 1 ";
    $sam_data = $this->db->query($sam_sql)->result_array();

    $bom_sql = "SELECT *
                FROM tbl_bom_article_1_req_consld as a
                INNER JOIN tbl_request_bom as e on a.bom_1_req_consld_id = e.bom_req_consld_id
                INNER JOIN tbl_request as b on e.request_id = b.request_id
                WHERE b.type = 3 AND b.deprt_approval = 0 AND a.req_sent_status = 1 ";
    $bom_data = $this->db->query($bom_sql)->result_array();

    // Process and append the CAD, Sample, and BOM requirements to the results
    foreach ($result as $key => $res) {
        $arr = $s_arr = [];

        // CAD data processing
        foreach ($cad_data as $value) {
            if ($value['request_id'] == $res['request_id']) {
                array_push($arr, $value['cad_requirement']);
                $status = getApprovalStatus($res['mgmt_approval']);
                array_push($s_arr, $status);
            }
        }

        // Sample data processing
        foreach ($sam_data as $value) {
            if ($value['request_id'] == $res['request_id']) {
                array_push($arr, $value['sample_requirement']);
                $status = getApprovalStatus($value['mgmt_approval']);
                array_push($s_arr, $status);
            }
        }

        // BOM data processing
        foreach ($bom_data as $value) {
            if ($value['request_id'] == $res['request_id']) {
                array_push($arr, 'BOM (A1)');
                $status = getApprovalStatus($value['mgmt_approval']);
                array_push($s_arr, $status);
            }
        }

        // Combine all the requirements and statuses
        $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
        $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);

        $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
        $result[$key]['cad_status'] = implode(' <br /> ', $s_arr);

        $result[$key]['bom_requirement'] = implode(' <br /> ', $arr);
        $result[$key]['bom_status'] = implode(' <br /> ', $s_arr);
    }

    return $result;
}

function getApprovalStatus($mgmt_approval) {
    if ($mgmt_approval == '0' || $mgmt_approval == '') {
        return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
    } else if ($mgmt_approval == '2') {
        return '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
    } else if ($mgmt_approval == '3') {
        return '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
    } else {
        return '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
    }
}

    public function getManageAllListt() {
       
         $sql_cadd = "SELECT a.*, a.flag as falgs, c.brandname, b.isriorcode, f.contactname as auth_name
                FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                LEFT JOIN ".KN_USERS." as f ON a.auth_by=f.id 
                WHERE a.type = 1 AND a.deprt_approval=0 and a.subscriberid = ".$this->db->escape($this->subscribid)." ORDER BY a.log DESC";
        $result_cad = $this->db->query($sql_cadd)->result_array();

         $sql_sampp = "SELECT a.*, a.flag as falgs,c.brandname, b.isriorcode, d.contactname as auth_name
                FROM tbl_request as a
                inner join kn_order_enquiry as b on a.enquiry_id=b.id
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                LEFT JOIN ".KN_USERS." as d ON  a.auth_by = d.id
                WHERE a.type = 2 and a.deprt_approval=0 and a.draft_status = 1 and a.subscriberid = ".$this->db->escape($this->subscribid)."
                ORDER BY a.log desc";
        $result_samp = $this->db->query($sql_sampp)->result_array();


         $sql_bomm = "SELECT a.*, a.flag as falgs, c.brandname, b.isriorcode, d.contactname as auth_name
                FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                LEFT JOIN ".KN_USERS." as d ON  a.auth_by= d.id
                WHERE a.type = 3  AND a.deprt_approval=0   and a.subscriberid = ".$this->db->escape($this->subscribid)." ORDER BY a.log DESC";
                //WHERE a.flag=1 AND a.type = 3 AND a.deprt_approval=0 AND a.draft_status=0";
        $result_bom = $this->db->query($sql_bomm)->result_array();

      
        

         $sql_bomm2 = "SELECT a.*, a.flag as falgs, c.brandname, b.isriorcode, d.contactname as auth_name
                FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                LEFT JOIN ".KN_USERS." as d ON  a.auth_by = d.id
                WHERE a.type = 4  AND a.deprt_approval=0 AND a.draft_status = 0  and a.subscriberid = ".$this->db->escape($this->subscribid)." ORDER BY a.log DESC";
                //WHERE a.flag=1 AND a.type = 3 AND a.deprt_approval=0 AND a.draft_status=0";
        $result_bom2 = $this->db->query($sql_bomm2)->result_array();

        $result = array_merge($result_cad, $result_samp, $result_bom, $result_bom2);

        usort($result, function($a, $b) {
        return strtotime($b['log']) - strtotime($a['log']);  // Sort by 'log' field in descending order
    });

        

       
        $cad_sql = "SELECT a.cad_requirement, a.request_id
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE e.type = 1 AND e.deprt_approval=0 ";
        $cad_data = $this->db->query($cad_sql)->result_array();

        $sam_sql = "SELECT *
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE   b.type = 2 and b.deprt_approval=0 and a.req_sent_status = 1 ";
        $sam_data = $this->db->query($sam_sql)->result_array();

        $bom_sql = "SELECT *
                FROM tbl_bom_article_1_req_consld as a
                INNER JOIN tbl_request_bom as e on a.bom_1_req_consld_id=e.bom_req_consld_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE  b.type = 3  and b.deprt_approval=0 AND a.req_sent_status = 1 ";

        $bom_data = $this->db->query($bom_sql)->result_array();

        $bom_sql2 = "SELECT *
                FROM tbl_bom_article_1_req_consld as a
                INNER JOIN tbl_request_bom as e on a.bom_1_req_consld_id=e.bom_req_consld_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE  b.type = 4  and b.deprt_approval=0 AND a.req_sent_status = 1 ";

        $bom_data2 = $this->db->query($bom_sql2)->result_array();
        $status=[];

       // print_r($bom_sql); exit;

        //$bom_data = $this->db->where('type',3)->where('deprt_approval',0)->order_by('log')->get('tbl_request')->result_array();

        foreach ($result as $key => $res) {
            $arr = $s_arr = [];
            $barr=[];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['cad_requirement']);
                    $status = '';
                    if($res['mgmt_approval'] == '0' || $res['mgmt_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($res['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                     else if($res['mgmt_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                }
            }

            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['sample_requirement']);
                    $status = '';
                    if($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($value['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                     else if($res['mgmt_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                }
            }

       //print_r($bom_data);
        foreach ($bom_data as $key2 => $value) {
    if ($value['request_id'] == $res['request_id']) {
       // array_push($arr, 'BOM (A1)');
        $barr= 'BOM (A1)';
        $status = '';
        if ($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '') {
            $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
        } else if ($value['mgmt_approval'] == '2') {
            $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
        }  
        else if($res['mgmt_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
        
        else {
            $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
        }
        array_push($s_arr, $status);
    }
}

foreach ($bom_data2 as $key2 => $value) {
    if ($value['request_id'] == $res['request_id']) {
       // array_push($arr, 'BOM (A1)');
        $barr= 'BOM (A2)';
         
        $status = '';
        if ($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '') {
            $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
        } else if ($value['mgmt_approval'] == '2') {
            $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
        }  
        else if($res['mgmt_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
        
        else {
            $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
        }
        array_push($s_arr, $status);
    }
}
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
        
            // $dis_arr = array_unique($arr);
            $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['cad_status'] = implode(' <br /> ', $s_arr);

            //$result[$key]['bom_requirement'] = implode(' <br /> ', $arr);
            //$result[$key]['bom_status'] = implode(' <br /> ', $s_arr);
             $result[$key]['bom_requirement'] =$barr;
             $result[$key]['bom_status'] = $status;
    
        }

	    return $result;

        //print_r($result);
    }

    public function getMerchantAllListt_____________() {
        // if($_SESSION['UI']['usertype'] == 2) {
        //     $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name
        //         FROM tbl_request as a
        //         INNER join kn_order_enquiry as b on a.enquiry_id=b.id
        //         INNER join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
        //         LEFT join ".KN_USERS." as d on a.req_by=d.id
        //         WHERE a.deprt_approval=0 AND a.draft_status=1
        //         ORDER BY a.log desc";
        // } else {
        
        //     $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name
        //         FROM tbl_request as a
        //         INNER join kn_order_enquiry as b on a.enquiry_id=b.id
        //         INNER join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
        //         LEFT join ".KN_USERS." as d on a.auth_by=d.id
        //         WHERE  a.deprt_approval=0 AND a.draft_status=0
        //         ORDER BY a.log desc";
        // }

        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name
        FROM tbl_request as a
        inner join kn_order_enquiry as b on a.enquiry_id=b.id
        inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
        LEFT JOIN ".KN_USERS." as d ON a.auth_by = d.id
        WHERE a.deprt_approval=0 and a.draft_status = 1 and a.req_by = " . $this->db->escape($this->userid)."
        ORDER BY a.log desc";
       $result = $this->db->query($sql)->result_array();

       
        $cad_sql = "SELECT a.cad_requirement, a.request_id
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE e.type = 1 AND e.deprt_approval=0 ORDER BY e.log DESC";
        $cad_data = $this->db->query($cad_sql)->result_array();

        $sam_sql = "SELECT *
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE   b.type = 2 and b.deprt_approval=0 and a.req_sent_status = 1 ORDER BY b.log DESC";
        $sam_data = $this->db->query($sam_sql)->result_array();

        $bom_sql = "SELECT *
                FROM tbl_bom_article_1_req_consld as a
                INNER JOIN tbl_request_bom as e on a.bom_1_req_consld_id=e.bom_req_consld_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE  b.type = 3 and b.deprt_approval=0 AND a.req_sent_status = 1 ORDER BY b.log DESC";


//WHERE  b.type = 3 and b.deprt_approval=0 AND b.draft_status=0 GROUP BY a.request_id ORDER BY b.log DESC";

        $bom_data = $this->db->query($bom_sql)->result_array();
       // print_r($bom_sql); exit;

        //$bom_data = $this->db->where('type',3)->where('deprt_approval',0)->order_by('log')->get('tbl_request')->result_array();

        foreach ($result as $key => $res) {
            $arr = $s_arr = [];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['cad_requirement']);
                    $status = '';
                    if($res['mgmt_approval'] == '0' || $res['mgmt_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($res['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                     else if($res['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                }
            }

            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['sample_requirement']);
                    $status = '';
                    if($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($value['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                }
            }

           foreach ($bom_data as $key2 => $value) {
                array_push($arr, 'BOM (A1)');
                $status = '';
                if($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '')
                {
                    $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                }
                else if($value['mgmt_approval'] == '2') {
                    $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                }
                else {
                    $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                }
                array_push($s_arr, $status);
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
        
            // $dis_arr = array_unique($arr);
            $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['cad_status'] = implode(' <br /> ', $s_arr);

            $result[$key]['bom_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['bom_status'] = implode(' <br /> ', $s_arr);
    
        }

	    return $result;
    }

    
    
    public function getManageAllListt_test() {
        if($_SESSION['UI']['usertype'] == 2) {
            $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name
                FROM tbl_request as a
                INNER join kn_order_enquiry as b on a.enquiry_id=b.id
                INNER join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                LEFT join ".KN_USERS." as d on a.req_by=d.id
                WHERE  a.deprt_approval=0 AND a.draft_status=0
                ORDER BY a.log desc";
        } else {
            $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name
                FROM tbl_request as a
                INNER join kn_order_enquiry as b on a.enquiry_id=b.id
                INNER join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                LEFT join ".KN_USERS." as d on a.auth_by=d.id
                WHERE   a.deprt_approval=0 AND a.draft_status=0
                ORDER BY a.log desc";
        }
        

        $result = $this->db->query($sql)->result_array();
        
        $cad_sql = "SELECT a.cad_requirement, a.request_id
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE  e.type = 1 AND e.deprt_approval=0 ORDER BY e.log DESC";
        $cad_data = $this->db->query($cad_sql)->result_array();

        $sam_sql = "SELECT *
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE  b.type = 2 and b.deprt_approval=0 and a.req_sent_status = 1 ORDER BY b.log DESC";
        $sam_data = $this->db->query($sam_sql)->result_array();

        $bom_sql = "SELECT *
                FROM tbl_bom_article_1_req_consld as a
                INNER JOIN tbl_request_bom as e on a.bom_1_req_consld_id=e.bom_req_consld_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE  b.type = 3 and b.deprt_approval=0 AND b.draft_status=0 GROUP BY a.request_id ORDER BY b.log DESC";

        $bom_data = $this->db->query($bom_sql)->result_array();
       // print_r($bom_sql); exit;

        //$bom_data = $this->db->where('type',3)->where('deprt_approval',0)->order_by('log')->get('tbl_request')->result_array();

        foreach ($result as $key => $res) {
            $arr = $s_arr = [];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['cad_requirement']);
                    $status = '';
                    if($res['mgmt_approval'] == '0' || $res['mgmt_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($res['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>';
                    }
                    array_push($s_arr, $status);
                }
            }

            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['sample_requirement']);
                    $status = '';
                    if($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($value['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                }
            }

           foreach ($bom_data as $key2 => $value) {
                array_push($arr, 'BOM (A1)');
                $status = '';
                if($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '')
                {
                    $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                }
                else if($value['mgmt_approval'] == '2') {
                    $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                }
                else {
                    $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                }
                array_push($s_arr, $status);
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
        
            // $dis_arr = array_unique($arr);
            $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['cad_status'] = implode(' <br /> ', $s_arr);

            $result[$key]['bom_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['bom_status'] = implode(' <br /> ', $s_arr);
    
        }

	    return $result;
    }

    // ********** Manage All request ENDS HERE *********** /

    // ********** LIST REQUEST DETAILS STARTS HERE *********** /
    public function getrequestDetailss($id, $reqId) {
        $req_sql = "SELECT a.*, b.contactname as auth_name FROM tbl_request as a 
            LEFT JOIN ".KN_USERS." as b ON a.auth_by=b.id WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT * FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' ";
                $data = $this->db->query($sql)->result_array();
            }
        }
        else {
            $data = [];
        }

        $result = $att_ref_data = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['request_cad_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value["req_size"]];

            $att_ref_data[$key] = [ $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'],
                            $value['buyer_sample'], $value['buyer_comment'] ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
        $PurposeData = ['Costing', 'Fab. Bulk Cons. Calculation', 'Dev. Sample Cutting', 'Order Conf. Sample Cuttting',
                            'Shipment Sample Cuttting', 'Production Bulk Cutting', 'Others'];
        $column = [
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "P.O. No. /\n Enq. Ref. No.", 'width' => '8%', 'align' => 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Combo / Colour", 'width' => '8%', 'aligh' => 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Size Spec", 'width' => '8%', 'align'=> 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Requirement", 'width' => '8%', 'align'=> 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Purpose", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $PurposeData, 'readOnly' => $readStatus],
            ['title' => "Category", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => ['New', 'In-line', 'Revised'], 'readOnly' => $readStatus],
            ['title' => "If Revised or In-line\nPrevious CAD Ref. No.", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => [], 'readOnly' => true],
            ['title' => "Required\nSize(s)", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $sizeMaster, 'multiple' => true, 'readOnly' => $readStatus ],
        ];

        // ******* GET THE COLUMN ENDS ********* //
        
        $ref_sql = "SELECT b.ref_queue_no as id, b.ref_queue_no as name, a.po_enq_ref_id as po_enq_id, a.combo_id, a.component_id, a.spec_code_id as size_id, a.cad_requirement as req_id 
            FROM tbl_cad_requirement as a 
            INNER JOIN tbl_request_cad as b ON a.cad_requirement_id=b.cad_id
            WHERE a.enquiry_id = " . $id . "  and a.req_sent_status = 1 and b.ref_queue_no != '' ";
        $ref_data = $this->db->query($ref_sql)->result_array();

        $output['column'] = $column;
        $output['data'] = $result;
        $output['requestData'] = $att_ref_data;
        $output['req_data'] = $req_data;
        $output['sizeData'] = $sizeMaster;
        $output['cadRefNo'] = $ref_data;
        return $output;
    }
   
    // ********** LIST REQUEST DETAILS ENDS HERE *********** /
    
    // ********** LIST REQUEST DETAILS STARTS HERE *********** /
    public function getReferenceDetailss($id, $reqId ) {
        $req_sql = "SELECT a.* FROM tbl_request as a WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT * FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId'";
                $data = $this->db->query($sql)->result_array();
            }
        }
        else {
            $data = [];
        }
        
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['request_cad_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['grad_measure_chart'], $value["artwork"], $value["measure_details"], $value["buyer_sample"], $value["buyer_comment"]];
        }

        // *** get garment size *** //
        $common_dd = [ [ "id" => '1', "name" => 'Attached'], ["id" => '2', "name" => 'Pending'], ["id" => '3', "name" => 'N.A.']];
        $column = [
            ['title' => "id", 'width' => '0%', 'align' => 'center', 'type'=> 'hidden'],
            ['title' => "P.O. No. /\n Enq. Ref. No.", 'width' => '8%', 'align' => 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Combo / Colour", 'width' => '8%', 'aligh' => 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Component", 'width' => '8%', 'align'=> 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Size Spec", 'width' => '8%', 'align'=> 'left', 'type' => 'text', 'readOnly' => true],
            ['title' => "Approved & Graded\n Measurement Chart", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $common_dd, 'readOnly' => true],
            ['title' => "Complete Artwork", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $common_dd, 'readOnly' => $readStatus],
            ['title' => "How to Measure\n Details", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' =>  $common_dd, 'readOnly' => $readStatus],
            ['title' => "Buyers Original \nSample or Pattern", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' =>  $common_dd, 'readOnly' => true],
            ['title' => "Buyer's Comments", 'width' => '8%', 'align'=> 'left', 'type' => 'dropdown', 'source' => $common_dd, 'readOnly' => $readStatus ],
        ];

        // ******* GET THE COLUMN ENDS ********* //
        $output['column'] = $column;
        $output['data'] = $result;
        return $output;
    }
    
    function getRequestData($enqId, $reqId)
    {
        $sql = "SELECT a.*,b.contactname as auth_name from tbl_request a
                LEFT JOIN ".KN_USERS." b ON a.auth_by=b.id
                WHERE a.request_id='$reqId' ";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    // ********** LIST REQUEST DETAILS ENDS HERE *********** /

    // ********** CAD request STARTS HERE *********** /

    public function getCADListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, f.contactname as auth_name
                FROM tbl_request as a 
                INNER JOIN kn_order_enquiry as b on a.enquiry_id=b.id 
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                LEFT JOIN ".KN_USERS." as f on a.auth_by=f.id 
                WHERE a.type = 1 AND a.deprt_approval=0 and a.req_by = ".$this->db->escape($this->userid)." ORDER BY a.log DESC";
        $result = $this->db->query($sql)->result_array();

        $cad_sql = "SELECT a.cad_requirement, a.request_id, e.log as logs, e.mgmt_approval
                FROM tbl_cad_requirement as a 
                INNER JOIN tbl_request as e on a.request_id=e.request_id
                WHERE  e.type = 1 AND e.deprt_approval=0 ORDER BY e.log DESC";
        $cad_data = $this->db->query($cad_sql)->result_array();

        foreach ($result as $key => $res) {
            $arr = $s_arr = $log_arr = [];
            
            foreach ($cad_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['cad_requirement']);
                    $status = '';
                    if($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '')
                    {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    else if($value['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    }
                    else if($value['mgmt_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    }
                    else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                    
                    $date = date('d-m-Y h:i A',strtotime($value['logs']));
                    array_push($log_arr, $date);
                    
                }
            }
        
            // $dis_arr = array_unique($arr);
            $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['cad_status'] = implode(' <br /> ', $s_arr);
            $result[$key]['logs'] = implode(' <br /> ', $log_arr);
    
        }
        //print_r($result);
    	return $result;
    }

   public function getBrandListt()
    {
        $userInfo = fnGetUserLoggedInfo(1);
        $this->db->from(KN_MASTER_BRANDS);
        $this->db->where('companyid', $userInfo['companyid']);
	    $query = $this->db->get();
	    $result = $query->result();
	    return $result;
    }


public function getCADListt_search($data)
{
    // Base WHERE clause
    $where = "a.type = 1 AND a.deprt_approval = 0 AND a.req_by = " . $this->db->escape($this->userid);

    // Filter by WIP reference number
    if (!empty($data['wip_ref_no'])) {
        $wip_ref_no = $this->db->escape_like_str($data['wip_ref_no']);
        $where .= " AND b.isriorcode LIKE '%" . $wip_ref_no . "%'";
    }


    // Filter by brand
    if (!empty($data['brandId'])) {
        $brandId = (int)$data['brandId'];
        //echo("branddd"+$brandId);
        $where .= " AND b.brandId = " . $brandId;
    }

    
    // Filter by status
    $mgmtValues = [];
    if (!empty($data['status'])) {
        $inputStatus = strtoupper($data['status']);
        $statusMap = [
            'PENDING'    => ['0', ''],
            'DECLINED'   => ['2'],
            'PENDING-RR' => ['3'],
            'AUTHORIZED' => ['1', '4', '5']
        ];

        foreach ($statusMap as $key => $values) {
            if (stripos($key, $inputStatus) !== false || stripos($inputStatus, $key) !== false) {
                $mgmtValues = array_merge($mgmtValues, $values);
            }
        }

        if (!empty($mgmtValues)) {
            $mgmtValues = array_map(function($v){ return "'" . $v . "'"; }, $mgmtValues);
            $where .= " AND a.mgmt_approval IN (" . implode(',', $mgmtValues) . ")";
        }
    }

    // Main request query
    $sql = "SELECT a.*, c.brandname, b.isriorcode, b.brandId, f.contactname as auth_name
            FROM tbl_request AS a
            INNER JOIN kn_order_enquiry AS b ON a.enquiry_id = b.id
            INNER JOIN " . KN_MASTER_BRANDS . " AS c ON b.brandId = c.id
            LEFT JOIN " . KN_USERS . " AS f ON a.auth_by = f.id where a.type = 1 AND a.deprt_approval = 0";

            if (!empty($data['wip_ref_no'])) {
        $sql .= " AND b.isriorcode LIKE '%" . $wip_ref_no . "%'";
    }
    if (!empty($data['RequestFrom'])) {
        $sql .= " AND b.brandId = " . $brandId;
    }
    if (!empty($data['brandId'])) {
        $sql .= " AND b.brandId = " . $brandId;
    }
    if (!empty($mgmtValues)) {
        $sql .= " AND a.mgmt_approval IN (" . implode(',', $mgmtValues) . ")";
    }

    if (!empty($data['RequestFrom']) && !empty($data['RequestTo'])) {
   $startDate = $this->changeReverseDate($data['RequestFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['RequestTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.req_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}

 if (!empty($data['cutoffFrom']) && !empty($data['cutoffTo'])) {
   $startDate = $this->changeReverseDate($data['cutoffFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['cutoffTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(a.cutoff_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}

 $sql .= " ORDER BY a.log DESC";

 //echo($sql);
           

    $result = $this->db->query($sql)->result_array();

    // CAD query
    $cad_sql = "SELECT a.cad_requirement, a.request_id, e.req_date,a.log AS logs, e.mgmt_approval
                FROM tbl_cad_requirement AS a
                INNER JOIN tbl_request AS e ON a.request_id = e.request_id
                INNER JOIN kn_order_enquiry AS b ON e.enquiry_id = b.id
                WHERE e.type = 1 AND e.deprt_approval = 0";

 if (!empty($data['wip_ref_no'])) {
        $cad_sql .= " AND b.isriorcode LIKE '%" . $wip_ref_no . "%'";
    }
    if (!empty($data['RequestFrom'])) {
        $cad_sql .= " AND b.brandId = " . $brandId;
    }
    if (!empty($data['brandId'])) {
        $cad_sql .= " AND b.brandId = " . $brandId;
    }
    if (!empty($mgmtValues)) {
        $cad_sql .= " AND e.mgmt_approval IN (" . implode(',', $mgmtValues) . ")";
    }

    if (!empty($data['cutoffFrom']) && !empty($data['cutoffTo'])) {
   $startDate = $this->changeReverseDate($data['cutoffFrom']) . '- 00:00:00' ;
    $endDate   = $this->changeReverseDate($data['cutoffTo']) .'- 00:00:00';
     $sql .= " AND STR_TO_DATE(e.cutoff_date, '%d/%m/%Y %h:%i %p') 
                  BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
   
}

    


   

    $cad_sql .= " ORDER BY e.log DESC";

    

    $cad_data = $this->db->query($cad_sql)->result_array();

    // Merge CAD data into main result
    foreach ($result as $key => $res) {
        $arr = $s_arr = $log_arr = [];

        foreach ($cad_data as $cad) {
            if ($cad['request_id'] == $res['request_id']) {
                $arr[] = $cad['cad_requirement'];

                // Determine status label
                $status = '';
                if ($cad['mgmt_approval'] === '0' || $cad['mgmt_approval'] === '') {
                    $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                } elseif ($cad['mgmt_approval'] === '2') {
                    $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                } elseif ($cad['mgmt_approval'] === '3') {
                    $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                } else {
                    $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                }
                $s_arr[] = $status;

                $log_arr[] = date('d-m-Y h:i A', strtotime($cad['logs']));
            }
        }

        $result[$key]['cad_requirement'] = implode(' <br /> ', $arr);
        $result[$key]['cad_status'] = implode(' <br /> ', $s_arr);
        $result[$key]['logs'] = implode(' <br /> ', $log_arr);
    }

    return $result;
}

 public function changeReverseDate($date)
    {
        $array=explode("-",$date);
        $rev=array_reverse($array);
        $date=implode("-",$rev);
        return $date;
    }

    // ********** CAD request ENDS HERE *********** /

    // ********** Sample request STARTS HERE *********** /

    public function getSampleListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name
                FROM tbl_request as a
                inner join kn_order_enquiry as b on a.enquiry_id=b.id
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                LEFT JOIN ".KN_USERS." as d ON a.auth_by = d.id
                WHERE a.type = 2 and a.deprt_approval=0 and a.draft_status = 1 and a.req_by = ".$this->db->escape($this->userid)."
                ORDER BY a.log desc";
        $result = $this->db->query($sql)->result_array();

        $sam_sql = "SELECT *
                FROM tbl_sample_requirement as a
                INNER JOIN tbl_request_sample as e on a.sample_requirement_id=e.sample_id
                INNER JOIN tbl_request as b on e.request_id=b.request_id
                WHERE b.type = 2 and b.deprt_approval=0 and a.req_sent_status = 1 ORDER BY b.log DESC";
        $sam_data = $this->db->query($sam_sql)->result_array();

        foreach ($result as $key => $res) {
            $arr = $s_arr = [];
            
            foreach ($sam_data as $key2 => $value) {
                if($value['request_id'] == $res['request_id'])
                {
                    array_push($arr, $value['sample_requirement']);
                    $status = '';
                    if($value['mgmt_approval'] == '0' || $value['mgmt_approval'] == '') {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    } else if($value['mgmt_approval'] == '2') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>DECLINED</strong></span>';
                    } else if($value['mgmt_approval'] == '3') {
                        $status = '<span class="text-light knRedColor bg-dark"><strong>PENDING-RR</strong></span>';
                    } else {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                    }
                    array_push($s_arr, $status);
                }
            }
        
            $result[$key]['sample_requirement'] = implode(' <br /> ', $arr);
            $result[$key]['sample_status'] = implode(' <br /> ', $s_arr);
    
        }

	    return $result;
    }

    // ********** Sample request ENDS HERE *********** /

    // ********** BOM request STARTS HERE *********** /

    public function getBOMListt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name
                FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                LEFT JOIN ".KN_USERS." as d ON a.auth_by = d.id
                WHERE a.type = 3 AND a.deprt_approval=0 AND a.draft_status=0  and a.req_by = ".$this->db->escape($this->userid)." ORDER BY a.log DESC";
                //WHERE a.flag=1 AND a.type = 3 AND a.deprt_approval=0 AND a.draft_status=0";
        $result = $this->db->query($sql)->result_array();
	    return $result;
    }

    public function getBOM2Listt() {
        $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name
                FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id 
                LEFT JOIN ".KN_USERS." as d ON a.auth_by = d.id
                WHERE a.type = 4 AND a.deprt_approval=0 AND a.draft_status=0  and a.req_by = ".$this->db->escape($this->userid)." ORDER BY a.log DESC";
                //WHERE a.flag=1 AND a.type = 3 AND a.deprt_approval=0 AND a.draft_status=0";
        $result = $this->db->query($sql)->result_array();
	    return $result;
    }
   

    //  public function getBOM2Listt() {
    //     $sql = "SELECT a.*, c.brandname, b.orderenqrefno FROM tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
    //             inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id WHERE a.flag=1 AND a.type = 4 and a.req_by = ".$this->db->escape($this->userid)." ORDER BY a.log DESC";
    //     $result = $this->db->query($sql)->result_array();
	//     return $result;
    // }


    // ********** BOM 2 request ENDS HERE *********** /

    // ********** EMBELLISHMENT request STARTS HERE *********** /

    public function getEmbellishmentListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno FROM tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id WHERE a.type = 11";
        $result = $this->db->query($sql)->result_array();
	    return $result;
    }

    // ********** EMBELLISHMENT request ENDS HERE *********** /

    // ********** Fabric request STARTS HERE *********** /

    public function getFabricListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno FROM tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id WHERE  a.type = 5 and a.req_by = ".$this->db->escape($this->userid);
        $result = $this->db->query($sql)->result_array();
	    return $result;
    }

    // ********** Fabric request ENDS HERE *********** /

    // ********** Production request STARTS HERE *********** /

    public function getProductionListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno FROM tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id WHERE a.type = 11 and a.req_by = ".$this->db->escape($this->userid);
        $result = $this->db->query($sql)->result_array();
	    return $result;
    }

    // ********** Production request ENDS HERE *********** /

    // ********** Vessel Booking request STARTS HERE *********** /

    public function getVesselListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno FROM tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id WHERE  a.type = 11";
        $result = $this->db->query($sql)->result_array();
	    return $result;
    }

    // ********** Vessel Booking request ENDS HERE *********** /

    // ********** Stationery request STARTS HERE *********** /

    public function getStationeryListt() {
        $sql = "SELECT a.*, c.brandname, b.orderenqrefno FROM tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id WHERE  a.type = 11";
        $result = $this->db->query($sql)->result_array();
	    return $result;
    }

    // ********** Stationery request ENDS HERE *********** /

    public function bomMITableData($id, $reqId) {
        $result = $this->db->from('tbl_request_sample a')
                    ->join('tbl_sample_requirement b', 'a.sample_id=b.sample_requirement_id', 'inner')
                    ->where('b.enquiry_id', $id)
                    ->where('a.request_id', $reqId)
                    ->where('req_sent_status', 1)
                    ->get()->result_array();
        return $result;
    }

    public function miDetails($id, $reqId) {
        $result = $this->db->from('tbl_mi_details')->where('request_id', $reqId)->get()->result_array();
        if(sizeof($result) == 0)
        {
            $result[0]['cad_req_date'] = '';
            $result[0]['cad_cutoff_date'] = '';
            $result[0]['cad_dept'] = '';
            $result[0]['fab_req_date'] = '';
            $result[0]['fab_cutoff_date'] = '';
            $result[0]['fab_dept'] = '';
            $result[0]['bom_req_date'] = '';
            $result[0]['bom_cutoff_date'] = '';
            $result[0]['bom_dept'] = '';
        }
        return $result;
    }

    // GET CAD REQUEST IMAGES

    public function getcadrequestImagess($data)
    {
        if(!isset($data['deptId']))
        {
            $result = $this->db->from('tbl_wip_files')
                ->where('enquiry_id', $data['enquiry_id'])
                ->where('request_id', $data['reqId'])
                ->where('type', $data['type'])
                ->get()->result_array();
            return $result;
        }
        else {
            $result = $this->db->from('tbl_wip_files')
                ->where('enquiry_id', $data['enquiry_id'])
                ->where('request_id', $data['reqId'])
                ->where('type', $data['type'])
                ->where('dept_id', $data['deptId'])
                ->get()->result_array();
            return $result;
        }
    }

    public function getbomrequestImagess($data)
    {
        $result = $this->db->from('tbl_wip_files')->where('enquiry_id', $data['enquiry_id'])->where('request_id', $data['reqId'])->where('type', $data['type'])->get()->result_array();
        return $result;
    }

    public function getcadqarequestDetailss($id, $reqId, $cadId) {
        $req_sql = "SELECT a.* FROM tbl_request as a WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT *, a.ref_queue_no as cad_ref_no FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' and b.qa_req_id='$cadId'  and (b.qa_approval=0 or b.qa_approval=2) ";
                $data = $this->db->query($sql)->result_array();
            }
        }
        else {
            $data = [];
        }

        $result = $att_ref_data = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [$value['cad_requirement_id'], $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value["req_size"], $value['cad_ref_no']];

            $att_ref_data[$key] = [ $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'],
                            $value['buyer_sample'], $value['buyer_comment'] ];
        }

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
        $PurposeData = ['Costing', 'Fab. Bulk Cons. Calculation', 'Dev. Sample Cutting', 'Order Conf. Sample Cuttting',
                            'Shipment Sample Cuttting', 'Production Bulk Cutting', 'Others'];

        // ******* GET THE COLUMN ENDS ********* //

        $output['data'] = $result;
        $output['requestData'] = $att_ref_data;
        $output['req_data'] = $req_data;
        $output['sizeData'] = $sizeMaster;
        $output['cad_data'] = $data;
        return $output;
    }

    public function getcadqarequestsentDetailss($id, $reqId ) {
        // $req_sql = "SELECT a.* FROM tbl_request as a WHERE a.enquiry_id='$id' and a.request_id='$reqId' and a.flag=1";
        // $req_data = $this->db->query($req_sql)->result_array();
        
        // $readStatus = true;
        // if(sizeof($req_data) > 0) {
        //     $type = $req_data[0]['type'];
        //     $data = [];
        //     if($type == 1) {
        //         $sql = "SELECT *, a.ref_queue_no as cad_ref_no FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
        //                 WHERE a.request_id='$reqId' and a.flag=1 and b.qa_req_sent_status = 0 and ( b.qa_status = 7 or b.qa_status = 4)";
        //         $data = $this->db->query($sql)->result_array();
        //     }
        // }
        // else {
        //     $data = [];
        // }
        $req_sql = "SELECT a.* FROM tbl_request as a WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT *,a.ref_queue_no as cad_ref_no FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' ";
                $data = $this->db->query($sql)->result_array();

                $qa_sql = "SELECT *,a.ref_queue_no as cad_ref_no FROM tbl_request_cad as a inner join tbl_cad_requirement as b on a.cad_id = b.cad_requirement_id 
                        WHERE a.request_id='$reqId' ";
                $qa_data = $this->db->query($qa_sql)->result_array();
                
            }
        }
        else {
            $data = [];
        }
        $result = [];
        $jobStatusData = [];
        $qaStatusData = [];
        //$result = $att_ref_data = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['cad_requirement_id'], false, $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value["req_size"], $value['cad_ref_no'], $value['job_status'], $value['qa_req_id'] ];

            $att_ref_data[$key] = [ $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'],
                            $value['buyer_sample'], $value['buyer_comment'] ];
        }

      

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
        $PurposeData = ['Costing', 'Fab. Bulk Cons. Calculation', 'Dev. Sample Cutting', 'Order Conf. Sample Cuttting',
                            'Shipment Sample Cuttting', 'Production Bulk Cutting', 'Others'];

        // ******* GET THE COLUMN ENDS ********* //

        $output['data'] = $result;
        $output['requestData'] = $att_ref_data;
        $output['req_data'] = $req_data;
        $output['sizeData'] = $sizeMaster;
        // $output['jobStatusData'] = array_values($jobStatusData);
        // $output['sizeData'] = $sizeMaster;
        return $output;
    }

    public function getsampleqarequestsentDetailss($id, $reqId ) {
        
        $req_sql = "SELECT a.* FROM tbl_request as a WHERE a.enquiry_id='$id' and a.request_id='$reqId' ";
        $req_data = $this->db->query($req_sql)->result_array();
        
        $readStatus = true;
        if(sizeof($req_data) > 0) {
            $type = $req_data[0]['type'];
            $data = [];
            if($type == 1) {
                $sql = "SELECT *,a.ref_queue_no as sample_ref_no FROM tbl_request_sample as a inner join tbl_sample_requirement as b on a.cad_id = b.sample_requirement_id 
                        WHERE a.request_id='$reqId' ";
                $data = $this->db->query($sql)->result_array();

                $qa_sql = "SELECT *,a.ref_queue_no as sample_ref_no FROM tbl_request_sample as a inner join tbl_sample_requirement as b on a.cad_id = b.sample_requirement_id 
                        WHERE a.request_id='$reqId' ";
                $qa_data = $this->db->query($qa_sql)->result_array();
                
            }
        }
        else {
            $data = [];
        }
        $result = [];
        $jobStatusData = [];
        $qaStatusData = [];
        //$result = $att_ref_data = [];
        foreach ($data as $key => $value)
        {
            $result[$key] = [ $value['cad_requirement_id'], false, $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'], $value['spec_code_id'],
                            $value['cad_requirement'], $value["purpose"], $value["category"], $value["if_revised"], $value["req_size"], $value['cad_ref_no'], $value['job_status'], $value['qa_req_id'] ];

            $att_ref_data[$key] = [ $value['po_enq_ref_id'], $value['combo_id'], $value['component_id'],
                            $value['spec_code_id'], $value['grad_measure_chart'], $value['artwork'], $value['measure_details'],
                            $value['buyer_sample'], $value['buyer_comment'] ];
        }

      

        // *** get garment size *** //
        $sizeChart  = $this->getSizeChart($id);
        $sizeMaster = $this->getSizeMasterDropdown($sizeChart);
        $PurposeData = ['Costing', 'Fab. Bulk Cons. Calculation', 'Dev. Sample Cutting', 'Order Conf. Sample Cuttting',
                            'Shipment Sample Cuttting', 'Production Bulk Cutting', 'Others'];

        // ******* GET THE COLUMN ENDS ********* //

        $output['data'] = $result;
        $output['requestData'] = $att_ref_data;
        $output['req_data'] = $req_data;
        $output['sizeData'] = $sizeMaster;
        // $output['jobStatusData'] = array_values($jobStatusData);
        // $output['sizeData'] = $sizeMaster;
        return $output;
    }
    
    
    public function deleteImageDetailss($data) {
        $this->db->where('wip_files_id', $data["id"]);
        $this->db->delete('tbl_wip_files');
        if($this->db->affected_rows() == '1')
        {
            $result["status"] = "success";
        }
        else
        {
            $result["status"] = "fail";
        }
        return $result;
    }
    
    public function getVendorDetailss() 
    {
        $sql = 'SELECT id, vendorname as name FROM kn_master_bom_vendor';
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

}
