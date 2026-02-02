<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//error_reporting(0);
class bom_store_model extends CI_Model {

    public function newItemListDetail($VarId) {
        $this->db->select('orderid,bompurindentid,bomPurchaseRequestId,purchaseIndentBomId');
        $VarQry    = $this->db->get_where(KN_STORES_NEW_ITEM_LIST, array('id' => $VarId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }
      public function __construct() {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->subscriberId = $ArrUserLoggedInfo['subscriber_id'];
        $this->mysqldatetime = date('d/m/Y h:i A');
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

    // public function getPurchaseIndentListt() {
    //     $sql = "SELECT a.*, c.brandname, b.isriorcode, e.*, f.*, g.vendorname FROM tbl_request as a 
    //             inner join kn_order_enquiry as b on a.enquiry_id=b.id 
    //             inner join kn_master_brands as c on b.brandId=c.id
    //             inner join tbl_request_status as e on a.request_id=e.request_id
    //             inner join tbl_request_payment as f on a.request_id=f.request_id
    //             inner join kn_master_bom_vendor as g on f.vendor_id=g.id
    //             where a.type=3 and a.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.bill_paid_status=1 and a.flag=1 GROUP BY e.request_id ORDER BY e.log DESC";
    //     $result = $this->db->query($sql)->result_array();
    //     return $result;
    // }
    
    public function getPurchaseIndentListt() {
         $sql = "SELECT a.*,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, h.cutoff_date, e.appr_status as appr_statuss, a.log as logs, g.vendorname,a.flag as flags FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                left join  tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                where  h.type IN (3, 4) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.supply_closed_status = 0 and a.flag IN (1,2 ) and e.type_of_mode ='M' and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id  ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();

        //  $sql = "SELECT a.*,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, h.cutoff_date, e.appr_status as appr_statuss, a.log as logs, g.vendorname,a.flag as flags FROM tbl_purchase_indent as a 
        //         inner join tbl_request as h on a.request_id=h.request_id
        //         inner join kn_order_enquiry as b on a.enquiry_id=b.id 
        //         inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
        //         inner join  tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
        //         left join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
        //         left join kn_master_bom_vendor as g on f.vendor_id=g.id
        //         where  h.type IN (3, 4) and h.mgmt_approval=1 and a.pi_list_status=1  and a.pi_appl_status=1 and a.supply_closed_status = 0 and a.flag IN (1,2 ) and e.type_of_mode ='M' and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id  ORDER BY h.log DESC";
        // $data = $this->db->query($sql)->result_array();

        return $data;
    }
    public function getPurchaseIndentListtBOM1() {
         $sql = "SELECT a.*,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, h.cutoff_date, e.appr_status as appr_statuss, a.log as logs, g.vendorname,a.flag as flags FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                left join  tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                where  h.type IN (3) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.supply_closed_status = 0 and a.flag IN (1,2 ) and e.type_of_mode ='M' and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id  ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();

        return $data;
    }
    public function getPurchaseIndentListtBOM2() {
         $sql = "SELECT a.*,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, h.cutoff_date, e.appr_status as appr_statuss, a.log as logs, g.vendorname,a.flag as flags FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                left join  tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                where  h.type IN (4) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and a.supply_closed_status = 0 and a.flag IN (1,2 ) and e.type_of_mode ='M' and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id  ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();

        return $data;
    }

    public function getSurplusPurchaseIndentListt() {
       
         $sql = "SELECT a.*,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, h.cutoff_date, e.appr_status as appr_statuss, a.log as logs, g.vendorname,a.flag as flags FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join  tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on f.vendor_id=g.id
                where  h.type IN (3, 4) and h.mgmt_approval=1 and a.pi_list_status=1   and a.p_type='surplus' and a.pi_appl_status=1 and a.supply_closed_status = 0 and a.flag IN (1,2 ) and e.type_of_mode ='M' and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id  ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();

         return $data;

        
    }
    public function getSurplusPurchaseIndentListtBOM1() {
          $sql = "SELECT a.*,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, h.cutoff_date, e.appr_status as appr_statuss, a.log as logs, g.vendorname,a.flag as flags FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join  tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on f.vendor_id=g.id
                where  h.type IN (3) and h.mgmt_approval=1 and a.pi_list_status=1   and a.p_type='surplus' and a.pi_appl_status=1 and a.supply_closed_status = 0 and a.flag IN (1,2 ) and e.type_of_mode ='M' and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id  ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();

         return $data;
    }
    public function getSurplusPurchaseIndentListtBOM2() {
         $sql = "SELECT a.*,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, h.cutoff_date, e.appr_status as appr_statuss, a.log as logs, g.vendorname,a.flag as flags FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join  tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                left join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                left join kn_master_bom_vendor as g on f.vendor_id=g.id
                where  h.type IN (4) and h.mgmt_approval=1 and a.pi_list_status=1 and a.p_type='surplus' and a.pi_appl_status=1 and a.supply_closed_status = 0 and a.flag IN (1,2 ) and e.type_of_mode ='M' and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id  ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();

         return $data;
    }
    
    //  public function getPurchaseIndentListt() {
    
    // $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.request_for, e.payment_requirement FROM 
    //             tbl_request as a inner join kn_order_enquiry as b on a.enquiry_id=b.id 
    //             inner join kn_master_brands as c on b.brandId=c.id 
    //             inner join kn_users as d on a.auth_by=d.id 
    //             left join tbl_request_status as e on a.request_id=e.request_id
    //             where a.type=3 and a.deprt_approval=1 and a.flag=1 GROUP BY a.request_id ORDER BY a.log DESC";
    //     $data = $this->db->query($sql)->result_array();

    //     $mer_sql = "SELECT b.contactname as merchant_name 
    //             FROM tbl_request as a
    //             inner join kn_users as b on a.req_by=b.id 
    //             WHERE a.flag=1 AND a.type = 3 and a.deprt_approval=1 ORDER BY a.log DESC";
    //     $mer_result = $this->db->query($mer_sql)->result_array();

    //     foreach ($data as $key => $value) {
    //         if($value['deprt_approval'] == 1) {
    //             $status = '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
    //         } else {
    //             $status = '<span class="text-light knOrangeColor bg-dark"><strong>SUPPLY CLOSED</strong></span>';
    //         }
    //         //$data[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
    //         $data[$key]['bom_status'] = $status;
    //     }

    //     return $data;
        
    //  }
    
    public function getSupplyclosurelistt() {
         $sql = "SELECT a.*,h.flag,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, a.log as logs, f.*, g.vendorname ,a.flag as flags FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                inner join tbl_bom_in_house as i on a.purchase_indent_id=i.purchase_indent_id
                where h.type IN (3, 4) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and i.supply_closed_status = 1 and a.flag IN (1,2 )  and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();
        //print_r($sql); exit;
        return $data;
    }
    public function getSupplyclosurelisttBOM1() {
         $sql = "SELECT a.*,h.flag,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, a.log as logs, f.*, g.vendorname ,a.flag as flags FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                inner join tbl_bom_in_house as i on a.purchase_indent_id=i.purchase_indent_id
                where h.type IN (3) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and i.supply_closed_status = 1 and a.flag IN (1,2 )  and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();
        //print_r($sql); exit;
        return $data;
    }
    public function getSupplyclosurelisttBOM2() {
         $sql = "SELECT a.*,h.flag,h.type,h.cutoff_date,h.supply_lead_time, c.brandname, b.isriorcode, e.*, a.log as logs, f.*, g.vendorname ,a.flag as flags FROM tbl_purchase_indent as a 
                inner join tbl_request as h on a.request_id=h.request_id
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join tbl_request_status as e on a.purchase_indent_id=e.purchase_indent_id
                inner join tbl_request_payment as f on a.purchase_indent_id=f.purchase_indent_id
                inner join kn_master_bom_vendor as g on f.vendor_id=g.id
                inner join tbl_bom_in_house as i on a.purchase_indent_id=i.purchase_indent_id
                where h.type IN (4) and h.mgmt_approval=1 and a.pi_list_status=1 and a.pi_appl_status=1 and i.supply_closed_status = 1 and a.flag IN (1,2 )  and h.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.purchase_indent_id ORDER BY a.log DESC";
        $data = $this->db->query($sql)->result_array();
        //print_r($sql); exit;
        return $data;
    }
    
    public function getOrderClosurelistt()
    {
        
        $res = [];
       
        $enqsql = "SELECT a.*,a.request_id as requestid, a.flag as flags,b.isriorcode,b.id,c.brandname FROM tbl_bom_in_house as a
                INNER JOIN tbl_purchase_indent as f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN tbl_request as d on f.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                WHERE d.type IN (3, 4) AND a.supply_closed_status=1 and a.supply_closed_status_moved=1 and a.order_closure_status_moved1=1   and d.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY b.id";
        $enqresult = $this->db->query($enqsql)->result_array();
        foreach($enqresult as $key => $value) {
             $item_desc = $brandname = $bcm = $garment_size = $appr_item_code = $appr_item_color_code = $size_dim = $uom = $received_qtys = $received_uom = $bom_ref_no = $bom_cutoff_date = $item_status = $log = [];
              
            $enquiry_id = $value['id'];
            $sql = "SELECT a.*, SUM(a.received_qty) as received_qtys, SUM(j.issued_qty) as issued_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.item_received_status FROM tbl_bom_in_house as a
                INNER JOIN tbl_purchase_indent as f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN tbl_request as d on f.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_details as g on f.request_id = g.req_bom_id
                LEFT JOIN tbl_mi_bom as h on g.request_id = h.request_id
                LEFT JOIN tbl_mi_bom_details as i on h.mi_bom_id = i.mi_bom_id
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                
                WHERE d.type IN (3, 4) AND a.order_stock_status=1 AND a.supply_closed_status=1  and a.order_closure_status_moved1=1 AND b.id = '$enquiry_id'  GROUP BY a.item_desc,a.garment_size,a.appr_item_code,a.appr_item_color_code,a.size_dim,a.uom ORDER BY a.appr_item_code ASC ";
            $result = $this->db->query($sql)->result_array();
           
            foreach($result as $rkey => $value1) {
                $enq_id = base64_encode($enquiry_id);
                $req_id = base64_encode($value1['request_id']);
                $item_code = base64_encode($value1['appr_item_code']);
                $pId = base64_encode($value1['purchase_indent_id']);
                
                array_push($appr_item_code, '<a class="bold" href="'.base_url().'request/Bomrequest/orderclosuredetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value1['appr_item_code'].'</a>');
                $item_desc[] = $value1['item_desc'];
                   //$item_desc[] = 'Wash Care Label   ';
                $bcm[] = $value1['bcm'];
                $garment_size[] = $value1['garment_size'];
                //$appr_item_code[] = $value1['appr_item_code'];
                $appr_item_color_code[] = $value1['appr_item_color_code'];
                $size_dim[] = $value1['size_dim'];
                $uom[] = $value1['uom'];
                if($value1['issued_qtys']) {
                    $issued_qtys = $value1['issued_qtys'];
                } else {
                    $issued_qtys = 0;
                }
                //$received_qtys[] = $value1['received_qtys'] - $issued_qtys;

                $this->db->select_sum('a.received_qty', 'total_qtys')
         ->from('tbl_bom_in_house a')
         ->where('a.appr_item_code', $value1['appr_item_code'])
         ->where('a.enquiryid', $enquiry_id);
         $total_received_qtys = $this->db->get()->row('total_qtys');


                
     $this->db->select('SUM(a.received_qty) AS total_qty,
    SUM(d.issued_qty) AS issued_qty,
    SUM(d.return_defective_qty) AS return_defective_qty,
    SUM(d.replace_defective_qty) AS replace_defective_qty,
    SUM(d.return_excess_qty) AS return_excess_qty');

    $this->db->from('tbl_bom_in_house a');
    $this->db->join('tbl_request b', 'a.request_id = b.request_id');
    $this->db->join('tbl_purchase_indent c', 'a.purchase_indent_id = c.purchase_indent_id');
    $this->db->join('tbl_mi_issued_details d', 'd.lot_no = a.bom_in_house_id');
    $this->db->where('a.order_stock_status', 1);
    $this->db->where('a.appr_item_code', $value1['appr_item_code']);
    $this->db->where('a.enquiryid', $enquiry_id);
    $query = $this->db->get();
    $result = $query->row();

      $received_qty = (float)$result->total_qty;
      $issued_qty = (float)$result->issued_qty;
      $def_qty = (float)$result->return_defective_qty;
      $rdef_qty = (float)$result->replace_defective_qty;
      $excess_qty = (float)$result->return_excess_qty;


       $total_qty = $total_received_qtys - $issued_qty  - $rdef_qty + $excess_qty;
                    
                 $received_qtys[] = $total_qty;


                 //print_r($received_qtys);
                 //die;
                 
                $received_uom[] = $value1['received_uom'];
                if($value1['bom_ref_no'] != '') {
                    $miId = $value1['bom_ref_no'];
                } else {
                    $miId = '-';
                }
                $bom_ref_no[] = $miId;
                if($value1['bom_cutoff_date'] != '') {
                    $cdate = $value1['bom_cutoff_date'];
                } else {
                    $cdate = '-';
                }
                $bom_cutoff_date[] = $cdate;
                
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>ORDER CLOSED</strong></span>';
                
                $item_status[] = $status;
                $log[] = date('d/m/Y h:i A',strtotime($value1['log']));
                
                
            }
            
            $isriorcode = '<a class="bold" href="'.base_url().'request/Bomrequest/storepurchaseindentdetails/'.$enq_id.'/reqId/'.$req_id.'/'.$pId.' " >'.$value['isriorcode'].'</a>';
            
       

            $res[$key]['isriorcode'] = $isriorcode;
            $res[$key]['brandname'] = $value['brandname'];
            $res[$key]['item_desc'] = implode('<span  style=" display:block; margin-bottom:10px; "></span>', $item_desc);
            $res[$key]['bcm'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $bcm);
            $res[$key]['garment_size'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $garment_size);
            $res[$key]['appr_item_code'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $appr_item_code);
            $res[$key]['appr_item_color_code'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $appr_item_color_code);
            $res[$key]['size_dim'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $size_dim);
            $res[$key]['uom'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $uom);
            $res[$key]['received_qtys'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $received_qtys);
            $res[$key]['received_uom'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $received_uom);
            $res[$key]['bom_ref_no'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $bom_ref_no);
            $res[$key]['bom_cutoff_date'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $bom_cutoff_date);
            $res[$key]['item_status'] = implode('<span style="display:block; margin-bottom:7px;"></span> ', $item_status);
            $res[$key]['log'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $log);
            $res[$key]['flag'] = $value['flags'];
            $res[$key]['request_id'] = $value['requestid'];


           
           
            
        }
        
        return $res;
    }

    public function getOrderClosurelistt_222222()
{
    $res = [];

    // 1) fetch enquiries
    $enqsql = "SELECT a.*, a.request_id AS requestid, a.flag AS flags,
                      b.isriorcode, b.id, c.brandname
               FROM tbl_bom_in_house a
               INNER JOIN tbl_purchase_indent f ON a.purchase_indent_id = f.purchase_indent_id
               INNER JOIN tbl_request d         ON f.request_id         = d.request_id
               INNER JOIN kn_order_enquiry b    ON d.enquiry_id         = b.id
               INNER JOIN ".KN_MASTER_BRANDS." c ON b.brandId           = c.id
               WHERE d.type IN (3,4)
                 AND a.supply_closed_status = 1
                 AND d.subscriberid = " . $this->db->escape($this->subscriberId) . "
               GROUP BY b.id";

    $enqresult = $this->db->query($enqsql)->result_array();

    foreach ($enqresult as $key => $enq) {
        $enquiry_id = (int)$enq['id'];

        // 2) per-enquiry items (rows for the mini-table)
        $sql = "SELECT a.*,
                       SUM(a.received_qty) AS received_qtys,
                       SUM(j.issued_qty)   AS issued_qtys,
                       c.brandname, b.id, b.isriorcode, d.*,
                       e.bcm, g.mi_id, g.bom_ref_no, g.bom_cutoff_date,
                       i.item_received_status
                FROM tbl_bom_in_house a
                INNER JOIN tbl_purchase_indent f ON a.purchase_indent_id = f.purchase_indent_id
                INNER JOIN tbl_request d         ON f.request_id         = d.request_id
                INNER JOIN kn_order_enquiry b    ON d.enquiry_id         = b.id
                INNER JOIN ".KN_MASTER_BRANDS." c ON b.brandId           = c.id
                INNER JOIN tbl_request_bom e     ON a.request_bom_id     = e.request_bom_id
                LEFT  JOIN tbl_mi_details g      ON f.request_id         = g.req_bom_id
                LEFT  JOIN tbl_mi_bom h          ON g.request_id         = h.request_id
                LEFT  JOIN tbl_mi_bom_details i  ON h.mi_bom_id          = i.mi_bom_id
                LEFT  JOIN tbl_mi_issued_details j ON i.mi_bom_details_id = j.mi_bom_details_id
                WHERE d.type IN (3,4)
                  AND a.order_stock_status   = 1
                  AND a.supply_closed_status = 1
                  AND b.id = {$enquiry_id}
                GROUP BY a.item_desc, a.garment_size, a.appr_item_code,
                         a.appr_item_color_code, a.size_dim, a.uom
                ORDER BY a.appr_item_code ASC";

        $rows  = $this->db->query($sql)->result_array();
        $items = [];                    // reset for this enquiry
        $lastReqId = null; $lastPid = null;

        foreach ($rows as $r) {
            // for links
            $enq_id   = base64_encode($enquiry_id);
            $req_id   = base64_encode($r['request_id']);
            $itemCode = base64_encode($r['appr_item_code']);
            $pId      = base64_encode($r['purchase_indent_id']);

            $lastReqId = $req_id; // keep last (any valid) to build the isriorcode link
            $lastPid   = $pId;

            $issued   = $r['issued_qtys'] ?: 0;
            $recvQty  = ($r['received_qtys'] ?? 0) - $issued;

            $items[] = [
             
                'bcm'                  => $r['bcm'],
                'garment_size'         => $r['garment_size'],
                'appr_item_code'       => '<a class="bold" href="'.base_url().'request/Bomrequest/orderclosuredetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$itemCode.'/pId/'.$pId.'">'.$r['appr_item_code'].'</a>',
                'appr_item_color_code' => $r['appr_item_color_code'],
                'size_dim'             => $r['size_dim'],
                'uom'                  => $r['uom'],
                'received_qtys'        => $recvQty,
                'received_uom'         => $r['received_uom'],
                'item_status'          => '<span class="text-light knOrangeColor bg-dark"><strong>ORDER CLOSED</strong></span>',
                'log'                  => !empty($r['log']) ? date('d/m/Y h:i A', strtotime($r['log'])) : '-',
            ];
        }

        // 3) render mini sub-table for this enquiry
        $detailsHtml = '<div class="details-wrap" style="max-height:220px; overflow:auto">'
                     . '<table class="mini" style="width:100%; border-collapse:collapse">';
        foreach ($items as $it) {
            $detailsHtml .= '<tr>'
                        
                          . '<td>'.$it['bcm'].'</td>'
                          . '<td>'.$it['garment_size'].'</td>'
                          . '<td>'.$it['appr_item_code'].'</td>'
                          . '<td>'.$it['appr_item_color_code'].'</td>'
                          . '<td>'.$it['size_dim'].'</td>'
                          . '<td>'.$it['uom'].'</td>'
                          . '<td>'.$it['received_qtys'].'</td>'
                          . '<td>'.$it['received_uom'].'</td>'
                          . '<td>'.$it['item_status'].'</td>'
                          . '<td>'.$it['log'].'</td>'
                          . '</tr>';
        }
        $detailsHtml .= '</table></div>';

        // 4) top-level columns for DataTables
        $enq_id = base64_encode($enquiry_id);
        $isriorHref = '#';
        if ($lastReqId !== null && $lastPid !== null) {
            $isriorHref = base_url().'request/Bomrequest/storepurchaseindentdetails/'.$enq_id.'/reqId/'.$lastReqId.'/'.$lastPid;
        }

        $res[$key] = [
            'isriorcode' => '<a class="bold" href="'.$isriorHref.'">'.$enq['isriorcode'].'</a>',
            'brandname'  => $enq['brandname'],
            'details'    => $detailsHtml,   // the mini-table goes in one column
            'flag'       => $enq['flags'],
            'request_id' => $enq['requestid'],
        ];
    }

    return $res;
}

    public function getOrderClosurelisttBOM1()
    {
        
        $res = [];
        
        $enqsql = "SELECT a.*,a.request_id as requestid, a.flag as flags,b.isriorcode,b.id,c.brandname FROM tbl_bom_in_house as a
                INNER JOIN tbl_purchase_indent as f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN tbl_request as d on f.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                WHERE d.type IN (3) AND a.supply_closed_status=1  and a.supply_closed_status_moved=1 and a.order_closure_status_moved1=1 and d.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY b.id";
        $enqresult = $this->db->query($enqsql)->result_array();
        foreach($enqresult as $key => $value) {
            $item_desc = $brandname = $bcm = $garment_size = $appr_item_code = $appr_item_color_code = $size_dim = $uom = $received_qtys = $received_uom = $bom_ref_no = $bom_cutoff_date = $item_status = $log = [];
        
            $enquiry_id = $value['id'];
            $sql = "SELECT a.*, SUM(a.received_qty) as received_qtys, SUM(j.issued_qty) as issued_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.item_received_status FROM tbl_bom_in_house as a
                INNER JOIN tbl_purchase_indent as f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN tbl_request as d on f.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_details as g on f.request_id = g.req_bom_id
                LEFT JOIN tbl_mi_bom as h on g.request_id = h.request_id
                LEFT JOIN tbl_mi_bom_details as i on h.mi_bom_id = i.mi_bom_id
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                
                WHERE d.type IN (3) AND a.order_stock_status=1 AND a.supply_closed_status=1 and a.order_closure_status_moved1=1 AND b.id= $enquiry_id  GROUP BY a.item_desc,a.garment_size,a.appr_item_code,a.appr_item_color_code,a.size_dim,a.uom ORDER BY a.appr_item_code ASC ";
            $result = $this->db->query($sql)->result_array();
            foreach($result as $rkey => $value1) {
                $enq_id = base64_encode($enquiry_id);
                $req_id = base64_encode($value1['request_id']);
                $item_code = base64_encode($value1['appr_item_code']);
                $pId = base64_encode($value1['purchase_indent_id']);
                
                array_push($appr_item_code, '<a class="bold" href="'.base_url().'request/Bomrequest/orderclosuredetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value1['appr_item_code'].'</a>');
                $item_desc[] = $value1['item_desc'];
                $bcm[] = $value1['bcm'];
                $garment_size[] = $value1['garment_size'];
                //$appr_item_code[] = $value1['appr_item_code'];
                $appr_item_color_code[] = $value1['appr_item_color_code'];
                $size_dim[] = $value1['size_dim'];
                $uom[] = $value1['uom'];
                if($value1['issued_qtys']) {
                    $issued_qtys = $value1['issued_qtys'];
                } else {
                    $issued_qtys = 0;
                }
                //$received_qtys[] = $value1['received_qtys'] - $issued_qtys;

                 $this->db->select_sum('a.received_qty', 'total_qtys')
         ->from('tbl_bom_in_house a')
         ->where('a.appr_item_code', $value1['appr_item_code'])
         ->where('a.enquiryid', $enquiry_id);
         $total_received_qtys = $this->db->get()->row('total_qtys');


                
     $this->db->select('SUM(a.received_qty) AS total_qty,
    SUM(d.issued_qty) AS issued_qty,
    SUM(d.return_defective_qty) AS return_defective_qty,
    SUM(d.replace_defective_qty) AS replace_defective_qty,
    SUM(d.return_excess_qty) AS return_excess_qty');

    $this->db->from('tbl_bom_in_house a');
    $this->db->join('tbl_request b', 'a.request_id = b.request_id');
    $this->db->join('tbl_purchase_indent c', 'a.purchase_indent_id = c.purchase_indent_id');
    $this->db->join('tbl_mi_issued_details d', 'd.lot_no = a.bom_in_house_id');
    $this->db->where('a.order_stock_status', 1);
    $this->db->where('a.appr_item_code', $value1['appr_item_code']);
    $this->db->where('a.enquiryid', $enquiry_id);
    $query = $this->db->get();
    $result = $query->row();

      $received_qty = (float)$result->total_qty;
      $issued_qty = (float)$result->issued_qty;
      $def_qty = (float)$result->return_defective_qty;
      $rdef_qty = (float)$result->replace_defective_qty;
      $excess_qty = (float)$result->return_excess_qty;


       $total_qty = $total_received_qtys - $issued_qty  - $rdef_qty + $excess_qty;

                $received_qtys[] = $total_qty;

                $received_uom[] = $value1['received_uom'];
                if($value1['bom_ref_no'] != '') {
                    $miId = $value1['bom_ref_no'];
                } else {
                    $miId = '-';
                }
                $bom_ref_no[] = $miId;
                if($value1['bom_cutoff_date'] != '') {
                    $cdate = $value1['bom_cutoff_date'];
                } else {
                    $cdate = '-';
                }
                $bom_cutoff_date[] = $cdate;
                
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>ORDER CLOSED</strong></span>';
                
                $item_status[] = $status;
                $log[] = date('d/m/Y h:i A',strtotime($value1['log']));
                
                
            }
            
            $isriorcode = '<a class="bold" href="'.base_url().'request/Bomrequest/storepurchaseindentdetails/'.$enq_id.'/reqId/'.$req_id.'/'.$pId.' " >'.$value['isriorcode'].'</a>';
            
             $res[$key]['isriorcode'] = $isriorcode;
            $res[$key]['brandname'] = $value['brandname'];
            $res[$key]['item_desc'] = implode('<span  style=" display:block; margin-bottom:10px; "></span>', $item_desc);
            $res[$key]['bcm'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $bcm);
            $res[$key]['garment_size'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $garment_size);
            $res[$key]['appr_item_code'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $appr_item_code);
            $res[$key]['appr_item_color_code'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $appr_item_color_code);
            $res[$key]['size_dim'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $size_dim);
            $res[$key]['uom'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $uom);
            $res[$key]['received_qtys'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $received_qtys);
            $res[$key]['received_uom'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $received_uom);
            $res[$key]['bom_ref_no'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $bom_ref_no);
            $res[$key]['bom_cutoff_date'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $bom_cutoff_date);
            $res[$key]['item_status'] = implode('<span style="display:block; margin-bottom:7px;"></span> ', $item_status);
            $res[$key]['log'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $log);
            $res[$key]['flag'] = $value['flags'];
            $res[$key]['request_id'] = $value['requestid'];
            
        }
        
        return $res;
    }
    public function getOrderClosurelisttBOM2()
    {
        
        $res = [];
        
        $enqsql = "SELECT a.*,a.request_id as requestid, a.flag as flags,b.isriorcode,b.id,c.brandname FROM tbl_bom_in_house as a
                INNER JOIN tbl_purchase_indent as f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN tbl_request as d on f.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                WHERE d.type IN (4) AND a.supply_closed_status=1  and a.supply_closed_status_moved=1 and d.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY b.id";
        $enqresult = $this->db->query($enqsql)->result_array();
        foreach($enqresult as $key => $value) {
            $item_desc = $brandname = $bcm = $garment_size = $appr_item_code = $appr_item_color_code = $size_dim = $uom = $received_qtys = $received_uom = $bom_ref_no = $bom_cutoff_date = $item_status = $log = [];
        
            $enquiry_id = $value['id'];
            $sql = "SELECT a.*, SUM(a.received_qty) as received_qtys, SUM(j.issued_qty) as issued_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.item_received_status FROM tbl_bom_in_house as a
                INNER JOIN tbl_purchase_indent as f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN tbl_request as d on f.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_details as g on f.request_id = g.req_bom_id
                LEFT JOIN tbl_mi_bom as h on g.request_id = h.request_id
                LEFT JOIN tbl_mi_bom_details as i on h.mi_bom_id = i.mi_bom_id
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                
                WHERE d.type IN (4) AND a.order_stock_status=1 AND a.supply_closed_status=1 AND b.id= $enquiry_id  GROUP BY a.item_desc,a.garment_size,a.appr_item_code,a.appr_item_color_code,a.size_dim,a.uom ORDER BY a.appr_item_code ASC ";
            $result = $this->db->query($sql)->result_array();
            foreach($result as $rkey => $value1) {
                $enq_id = base64_encode($enquiry_id);
                $req_id = base64_encode($value1['request_id']);
                $item_code = base64_encode($value1['appr_item_code']);
                $pId = base64_encode($value1['purchase_indent_id']);
                
                array_push($appr_item_code, '<a class="bold" href="'.base_url().'request/Bomrequest/orderclosuredetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value1['appr_item_code'].'</a>');
                $item_desc[] = $value1['item_desc'];
                $bcm[] = $value1['bcm'];
                $garment_size[] = $value1['garment_size'];
                //$appr_item_code[] = $value1['appr_item_code'];
                $appr_item_color_code[] = $value1['appr_item_color_code'];
                $size_dim[] = $value1['size_dim'];
                $uom[] = $value1['uom'];
                if($value1['issued_qtys']) {
                    $issued_qtys = $value1['issued_qtys'];
                } else {
                    $issued_qtys = 0;
                }
                $received_qtys[] = $value1['received_qtys'] - $issued_qtys;
                $received_uom[] = $value1['received_uom'];
                if($value1['bom_ref_no'] != '') {
                    $miId = $value1['bom_ref_no'];
                } else {
                    $miId = '-';
                }
                $bom_ref_no[] = $miId;
                if($value1['bom_cutoff_date'] != '') {
                    $cdate = $value1['bom_cutoff_date'];
                } else {
                    $cdate = '-';
                }
                $bom_cutoff_date[] = $cdate;
                
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>ORDER CLOSED</strong></span>';
                
                $item_status[] = $status;
                $log[] = date('d/m/Y h:i A',strtotime($value1['log']));
                
                
            }
            
            $isriorcode = '<a class="bold" href="'.base_url().'request/Bomrequest/storepurchaseindentdetails/'.$enq_id.'/reqId/'.$req_id.'/'.$pId.' " >'.$value['isriorcode'].'</a>';
            
           $res[$key]['isriorcode'] = $isriorcode;
            $res[$key]['brandname'] = $value['brandname'];
            $res[$key]['item_desc'] = implode('<span  style=" display:block; margin-bottom:10px; "></span>', $item_desc);
            $res[$key]['bcm'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $bcm);
            $res[$key]['garment_size'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $garment_size);
            $res[$key]['appr_item_code'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $appr_item_code);
            $res[$key]['appr_item_color_code'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $appr_item_color_code);
            $res[$key]['size_dim'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $size_dim);
            $res[$key]['uom'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $uom);
            $res[$key]['received_qtys'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $received_qtys);
            $res[$key]['received_uom'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $received_uom);
            $res[$key]['bom_ref_no'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $bom_ref_no);
            $res[$key]['bom_cutoff_date'] = implode('<span style="display:block; margin-bottom:10px;"></span> ', $bom_cutoff_date);
            $res[$key]['item_status'] = implode('<span style="display:block; margin-bottom:7px;"></span> ', $item_status);
            $res[$key]['log'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $log);
            $res[$key]['flag'] = $value['flags'];
            $res[$key]['request_id'] = $value['requestid'];
            
        }
        
        return $res;
    }

//     public function getBOMMIReceivedListt() {
//         $sql = "SELECT a.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
//                 inner join kn_order_enquiry as b on a.enquiry_id=b.id 
//                 inner join kn_master_brands as c on b.brandId=c.id
//                 inner join kn_users as d on a.auth_by=d.id
//                 inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
//                 GROUP BY a.request_id  ORDER BY a.log DESC";
//         $result = $this->db->query($sql)->result_array();
// // print_r($sql); exit;
//         $mer_sql = "SELECT d.contactname as merchant_name FROM tbl_request as a 
//                 inner join kn_users as d on a.req_by=d.id
//                 inner join tbl_mi_details as e on a.request_id=e.request_id
//                 GROUP BY a.request_id ORDER BY a.log DESC";
//         $mer_result = $this->db->query($mer_sql)->result_array();

//         foreach ($result as $key => $value) {
//             $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
//         }

//         return $result;
//     }
    
    public function getBOMMIReceivedListt() {
      
         $sql = "SELECT a.*, f.*, c.brandname, e.bom_dept,b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                  and a.subscriberid = " . $this->db->escape($this->subscriberId)."  ORDER BY f.log DESC";
        
        $bom_data = $this->db->query($sql)->result_array();
     //print_r($bom_data); exit;

       
        
        $bom_mi_tbl_data = [];
        
        $bom_ref_nos = [];
        
        //for ($i=0; $i < sizeof($bom_data); $i++) { 
        foreach($bom_data as $key1 => $value1) {
            $bom_sql1 = "SELECT a.*,c.bom_dept,c.bom_ref_no FROM tbl_mi_bom_details as a
                INNER JOIN tbl_mi_bom as b on a.mi_bom_id = b.mi_bom_id
                INNER JOIN tbl_mi_details as c on b.request_id = c.request_id
                WHERE a.mi_bom_id = ".$value1['mi_bom_id']." ";
            $bom_details_data = $this->db->query($bom_sql1)->result_array();  
                
            array_push($bom_mi_tbl_data, $bom_details_data);
        }

        //print_r($bom_sql1); exit;
        // print_r(count($bom_details_data));
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                if($nValue['bom_status'] == 0) 
                {
                    $status = false;
                }
                else {
                    $status = true;
                }
                $tot_qty = 0;
                $dc_no = [];
                $dc_date = [];
                $uom = [];
                $bom_ref_no = "'".$nValue['bom_ref_no']."'";
                $sample_no = "'".$nValue['sample_no']."'";
                
                $issue_sql = "SELECT * FROM tbl_mi_issued_details as a WHERE a.mi_ref_no = ".$bom_ref_no." AND a.mi_serial_no = ".$sample_no." AND a.mi_bom_details_id = ".$nValue['mi_bom_details_id']." ";
                $issue_data = $this->db->query($issue_sql)->result_array(); 
                
                
                
                foreach($issue_data as $key2 => $value2) {
                    
                    array_push($uom, $value2['uom']);
                    $tot_qty += $value2['issued_qty'];
                }
                $uom = array_unique($uom);
                if($tot_qty == 0) {
                    $issuse_status = 'PENDING'; 
                } else if($nValue['ind_qty'] == $tot_qty) {
                    $issuse_status = 'ISSUED - FULL';
                } else {
                    $issuse_status = 'ISSUED - PART';
                }
                
                $bom_ref_nos[$nValue['bom_ref_no']][] = $issuse_status;
            }
        }
        //print_r($bom_ref_nos); exit;
        $mi_ref_no = $status1 = [];
        //print_r($bom_ref_nos);
        foreach($bom_ref_nos as $key2 => $value2) {
            if(in_array('PENDING',$value2) || in_array('ISSUED - PART',$value2)) {
                $mi_ref_no[] = $key2;
                if(in_array('PENDING',$value2)) {
                    $status1[] = 'PENDING';
                } else {
                    $status1[] = 'ISSUED - PART';
                }
            } else {
                
                $mi_ref_no[] = $key2;
                $status1[] = 'ISSUED - FULL';
                
            }
        } 
       $result = $result1 = [];
       for($i=0;$i<sizeof($mi_ref_no);$i++) {
           $mi_ref_no1 = "'".$mi_ref_no[$i]."'";
        $sql1 = "SELECT a.*, f.*, c.brandname, e.bom_dept,b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                where e.bom_ref_no = ".$mi_ref_no1." GROUP BY a.request_id  ORDER BY a.log DESC";
        $result[] = $this->db->query($sql1)->result_array();
        
       }
       
       foreach($result as $key3 => $value3) {
           $nValue1['status'] = '';
           foreach ($value3 as $nKey1 => $nValue1) {
               $nValue1['status'] = $status1[$key3];
               $result1[] = $nValue1;
           }
       }
       
        return $result1;
    }
    
    public function getBOMMIPendingListt() {
        
        $sql = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                  and a.subscriberid = " . $this->db->escape($this->subscriberId)."  ORDER BY f.log DESC";
        
        $bom_data = $this->db->query($sql)->result_array();
     //print_r($sql); exit;

       
        
        $bom_mi_tbl_data = [];
        
        $bom_ref_nos = [];
        
        //for ($i=0; $i < sizeof($bom_data); $i++) { 
        foreach($bom_data as $key1 => $value1) {
            $bom_sql1 = "SELECT a.*,c.bom_ref_no FROM tbl_mi_bom_details as a
                INNER JOIN tbl_mi_bom as b on a.mi_bom_id = b.mi_bom_id
                INNER JOIN tbl_mi_details as c on b.request_id = c.request_id
                WHERE a.mi_bom_id = ".$value1['mi_bom_id']." ";
            $bom_details_data = $this->db->query($bom_sql1)->result_array();  
                
            array_push($bom_mi_tbl_data, $bom_details_data);
        }

        //print_r($bom_sql1); exit;
        // print_r(count($bom_details_data));
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                if($nValue['bom_status'] == 0) 
                {
                    $status = false;
                }
                else {
                    $status = true;
                }
                $tot_qty = 0;
                $dc_no = [];
                $dc_date = [];
                $uom = [];
                $bom_ref_no = "'".$nValue['bom_ref_no']."'";
                $sample_no = "'".$nValue['sample_no']."'";
                
                $issue_sql = "SELECT * FROM tbl_mi_issued_details as a WHERE a.mi_ref_no = ".$bom_ref_no." AND a.mi_serial_no = ".$sample_no." AND a.mi_bom_details_id = ".$nValue['mi_bom_details_id']." ";
                $issue_data = $this->db->query($issue_sql)->result_array(); 
                
                
                
                foreach($issue_data as $key2 => $value2) {
                    
                    array_push($uom, $value2['uom']);
                    $tot_qty += $value2['issued_qty'];
                }
                $uom = array_unique($uom);
                if($tot_qty == 0) {
                    $issuse_status = 'PENDING'; 
                } else if($nValue['ind_qty'] == $tot_qty) {
                    $issuse_status = 'ISSUED - FULL';
                } else {
                    $issuse_status = 'ISSUED - PART';
                }
                
                $bom_ref_nos[$nValue['bom_ref_no']][] = $issuse_status;
            }
        }
        //print_r($bom_ref_nos); exit;
        $mi_ref_no = $status1 = [];
        //print_r($bom_ref_nos);
        foreach($bom_ref_nos as $key2 => $value2) {
            if(in_array('PENDING',$value2) || in_array('ISSUED - PART',$value2)) {
              
                if(in_array('PENDING',$value2)) {
                    $mi_ref_no[] = $key2;
                    $status1[] = 'PENDING';
                      
                } else {
                    //$status1[] = 'ISSUED - PART';
                }
            } else {
                
                //$mi_ref_no[] = $key2;
                //$status1[] = 'ISSUED - FULL';
                
            }
        } 
       $result = $result1 = [];
       for($i=0;$i<sizeof($mi_ref_no);$i++) {
           $mi_ref_no1 = "'".$mi_ref_no[$i]."'";
        $sql1 = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                where e.bom_ref_no = ".$mi_ref_no1." GROUP BY a.request_id  ORDER BY a.log DESC";
        $result[] = $this->db->query($sql1)->result_array();
        
       }
       
       foreach($result as $key3 => $value3) {
           $nValue1['status'] = '';
           foreach ($value3 as $nKey1 => $nValue1) {
               $nValue1['status'] = $status1[$key3];
               $result1[] = $nValue1;
           }
       }
       
          return $result1;
    }

     public function getBOMMIpartPendingListt() {
        
        $sql = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                  and a.subscriberid = " . $this->db->escape($this->subscriberId)."  ORDER BY f.log DESC";
        
        $bom_data = $this->db->query($sql)->result_array();
     //print_r($sql); exit;

       
        
        $bom_mi_tbl_data = [];
        
        $bom_ref_nos = [];
        
        //for ($i=0; $i < sizeof($bom_data); $i++) { 
        foreach($bom_data as $key1 => $value1) {
            $bom_sql1 = "SELECT a.*,c.bom_ref_no FROM tbl_mi_bom_details as a
                INNER JOIN tbl_mi_bom as b on a.mi_bom_id = b.mi_bom_id
                INNER JOIN tbl_mi_details as c on b.request_id = c.request_id
                WHERE a.mi_bom_id = ".$value1['mi_bom_id']." ";
            $bom_details_data = $this->db->query($bom_sql1)->result_array();  
                
            array_push($bom_mi_tbl_data, $bom_details_data);
        }

        //print_r($bom_sql1); exit;
        // print_r(count($bom_details_data));
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                if($nValue['bom_status'] == 0) 
                {
                    $status = false;
                }
                else {
                    $status = true;
                }
                $tot_qty = 0;
                $dc_no = [];
                $dc_date = [];
                $uom = [];
                $bom_ref_no = "'".$nValue['bom_ref_no']."'";
                $sample_no = "'".$nValue['sample_no']."'";
                
                $issue_sql = "SELECT * FROM tbl_mi_issued_details as a WHERE a.mi_ref_no = ".$bom_ref_no." AND a.mi_serial_no = ".$sample_no." AND a.mi_bom_details_id = ".$nValue['mi_bom_details_id']." ";
                $issue_data = $this->db->query($issue_sql)->result_array(); 
                
                
                
                foreach($issue_data as $key2 => $value2) {
                    
                    array_push($uom, $value2['uom']);
                    $tot_qty += $value2['issued_qty'];
                }
                $uom = array_unique($uom);
                if($tot_qty == 0) {
                    $issuse_status = 'PENDING'; 
                } else if($nValue['ind_qty'] == $tot_qty) {
                    $issuse_status = 'ISSUED - FULL';
                } else {
                    $issuse_status = 'ISSUED - PART';
                }
                
                $bom_ref_nos[$nValue['bom_ref_no']][] = $issuse_status;
            }
        }
        //print_r($bom_ref_nos); exit;
        $mi_ref_no = $status1 = [];
        //print_r($bom_ref_nos);
        foreach($bom_ref_nos as $key2 => $value2) {
            if(in_array('PENDING',$value2) || in_array('ISSUED - PART',$value2)) {
               
                if(in_array('ISSUED - PART',$value2)) {
                     $mi_ref_no[] = $key2;
                    $status1[] = 'ISSUED - PART';
                } else {
                   // $status1[] = 'ISSUED - PART';
                    //$mi_ref_no[] = $key2;
                }
            } else {
                
                //$mi_ref_no[] = $key2;
                //$status1[] = 'ISSUED - FULL';
                
            }
        } 
       $result = $result1 = [];
       for($i=0;$i<sizeof($mi_ref_no);$i++) {
           $mi_ref_no1 = "'".$mi_ref_no[$i]."'";
        $sql1 = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                where e.bom_ref_no = ".$mi_ref_no1." GROUP BY a.request_id  ORDER BY a.log DESC";
        $result[] = $this->db->query($sql1)->result_array();
        
       }
       
       foreach($result as $key3 => $value3) {
           $nValue1['status'] = '';
           foreach ($value3 as $nKey1 => $nValue1) {
               $nValue1['status'] = $status1[$key3];
               $result1[] = $nValue1;
           }
       }
       
          return $result1;
    }
    
    public function getBOMMIIssuedListt() {
        
        $sql = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                  and a.subscriberid = " . $this->db->escape($this->subscriberId)."  ORDER BY f.log DESC";
        
        $bom_data = $this->db->query($sql)->result_array();
     //print_r($sql); exit;

       
        
        $bom_mi_tbl_data = [];
        
        $bom_ref_nos = [];
        
        //for ($i=0; $i < sizeof($bom_data); $i++) { 
        foreach($bom_data as $key1 => $value1) {
            $bom_sql1 = "SELECT a.*,c.bom_ref_no FROM tbl_mi_bom_details as a
                INNER JOIN tbl_mi_bom as b on a.mi_bom_id = b.mi_bom_id
                INNER JOIN tbl_mi_details as c on b.request_id = c.request_id
                WHERE a.mi_bom_id = ".$value1['mi_bom_id']." ";
            $bom_details_data = $this->db->query($bom_sql1)->result_array();  
                
            array_push($bom_mi_tbl_data, $bom_details_data);
        }

        //print_r($bom_sql1); exit;
        // print_r(count($bom_details_data));
        foreach ($bom_mi_tbl_data as $key => $value) {
            foreach ($value as $nKey => $nValue) {
                if($nValue['bom_status'] == 0) 
                {
                    $status = false;
                }
                else {
                    $status = true;
                }
                $tot_qty = 0;
                $dc_no = [];
                $dc_date = [];
                $uom = [];
                $bom_ref_no = "'".$nValue['bom_ref_no']."'";
                $sample_no = "'".$nValue['sample_no']."'";
                
                $issue_sql = "SELECT * FROM tbl_mi_issued_details as a WHERE a.mi_ref_no = ".$bom_ref_no." AND a.mi_serial_no = ".$sample_no." AND a.mi_bom_details_id = ".$nValue['mi_bom_details_id']." ";
                $issue_data = $this->db->query($issue_sql)->result_array(); 
                
                
                
                foreach($issue_data as $key2 => $value2) {
                    
                    array_push($uom, $value2['uom']);
                    $tot_qty += $value2['issued_qty'];
                }
                $uom = array_unique($uom);
                if($tot_qty == 0) {
                    $issuse_status = 'PENDING'; 
                } else if($nValue['ind_qty'] == $tot_qty) {
                    $issuse_status = 'ISSUED - FULL';
                } else {
                    $issuse_status = 'ISSUED - PART';
                }
                
                $bom_ref_nos[$nValue['bom_ref_no']][] = $issuse_status;
            }
        }
        //print_r($bom_ref_nos); exit;
        $mi_ref_no = $status1 = [];
        //print_r($bom_ref_nos);
        foreach($bom_ref_nos as $key2 => $value2) {
            if(in_array('PENDING',$value2) || in_array('ISSUED - PART',$value2)) {
                //$mi_ref_no[] = $key2;
                if(in_array('PENDING',$value2)) {
                    //$status1[] = 'PENDING';
                } else {
                    //$status1[] = 'ISSUED - PART';
                }
            } else {
                
                $mi_ref_no[] = $key2;
                $status1[] = 'ISSUED - FULL';
                
            }
        } 
       $result = $result1 = [];
       for($i=0;$i<sizeof($mi_ref_no);$i++) {
           $mi_ref_no1 = "'".$mi_ref_no[$i]."'";
        $sql1 = "SELECT a.*, f.*, c.brandname, b.isriorcode, d.contactname as auth_name, e.bom_ref_no FROM tbl_request as a 
                inner join kn_order_enquiry as b on a.enquiry_id=b.id 
                inner join ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                inner join ".KN_USERS." as d on a.auth_by=d.id
                inner join tbl_mi_details as e on a.request_id=e.request_id AND a.mgmt_approval=1 AND a.deprt_approval=1
                inner join tbl_mi_bom as f on e.request_id = f.request_id 
                where e.bom_ref_no = ".$mi_ref_no1." GROUP BY a.request_id  ORDER BY a.log DESC";
        $result[] = $this->db->query($sql1)->result_array();
        
       }
       
       foreach($result as $key3 => $value3) {
           $nValue1['status'] = '';
           foreach ($value3 as $nKey1 => $nValue1) {
               $nValue1['status'] = $status1[$key3];
               $result1[] = $nValue1;
           }
       }
       
        return $result1;
    }
    
    public function getOrderStockListt()
    {
        

        $sql = "SELECT a.*, g.request_id as request_ids, SUM(a.received_qty) as received_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.mi_bom_details_id,i.ind_qty, i.item_received_status as irs FROM tbl_bom_in_house as a
                INNER JOIN tbl_request as d on a.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_bom_details as i on a.appr_item_code = i.item_code AND i.item_received_status = 0
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                LEFT JOIN tbl_mi_bom as h on i.mi_bom_id = h.mi_bom_id
                LEFT JOIN tbl_mi_details as g on h.request_id = g.request_id
                WHERE d.type=3 AND a.order_stock_status=1 AND a.supply_closed_status=1 AND g.bom_ref_no != '' GROUP BY a.appr_item_code,b.id,g.bom_ref_no ORDER BY a.log DESC ";
        
            $result = $this->db->query($sql)->result_array();
        $k=0;
        foreach($result as $key => $value) {
            $tot_qty = 0;
            $total_issue_qty = 0;
            if($value['mi_bom_details_id'] != '') {
                $ref_sql = "SELECT * FROM tbl_mi_issued_details WHERE mi_bom_details_id = ".$value['mi_bom_details_id']." ";
                $ref_data = $this->db->query($ref_sql)->result_array();
            
                $bom_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_details_id = ".$value['mi_bom_details_id']." ";
                $ind_qty = $this->db->query($bom_sql)->row()->ind_qty;
                $tot_qty =$ind_qty;
                foreach ($ref_data as $key1 => $res1) {
                    $total_issue_qty += $res1['issued_qty'];
                }
            }
            
            if($tot_qty == $total_issue_qty) {
                $show_status = 'No';
                $status = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED-FULL</strong></span>';
            } else if($total_issue_qty > 0) {
                $show_status = 'Yes';
                $status = '<span class="text-light knBlueColor bg-dark"><strong>ISSUED-PART</strong></span>';
            } else {
                $show_status = 'Yes';
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            }
            
            if($show_status == 'Yes') {
            $code = $value['appr_item_code'];
            $enq_id = base64_encode($value['id']);
            $req_id = base64_encode($value['request_ids']);
            $item_code = base64_encode($value['appr_item_code']);
            $pId = base64_encode($value['purchase_indent_id']);
            
            $item_codess = '<a class="bold" href="'.base_url().'request/Bomrequest/orderstockdetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value['appr_item_code'].'</a>';
            $res[$k]['isriorcode'] = $value['isriorcode'];
            $res[$k]['brandname'] = $value['brandname'];
            $res[$k]['item_desc'] = $value['item_desc'];
            $res[$k]['garment_size'] = $value['garment_size'];
            $res[$k]['appr_item_code'] = $item_codess;
            $res[$k]['appr_item_color_code'] = $value['appr_item_color_code'];
            $res[$k]['size_dim'] = $value['size_dim'];
            $res[$k]['uom'] = $value['uom'];
            $res[$k]['received_qtys'] = $value['received_qtys'];
            $res[$k]['received_uom'] = $value['received_uom'];
            $res[$k]['bom_ref_no'] = $value['bom_ref_no'];
            $res[$k]['bom_cutoff_date'] = $value['bom_cutoff_date'];
            $res[$k]['item_status'] = $status;

            $res[$k]['log'] = date('d-m-Y h:i A',strtotime($value['log']));
            $k++;
            }
            
        }
        
        return $res;
        
    }
    
    
    public function getOrderIssuedListt()
    {
        

        $sql = "SELECT a.*, g.request_id as request_ids, SUM(a.received_qty) as received_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.mi_bom_details_id,i.ind_qty, i.item_received_status as irs FROM tbl_bom_in_house as a
                INNER JOIN tbl_request as d on a.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_bom_details as i on a.appr_item_code = i.item_code AND i.item_received_status = 0
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                LEFT JOIN tbl_mi_bom as h on i.mi_bom_id = h.mi_bom_id
                LEFT JOIN tbl_mi_details as g on h.request_id = g.request_id
                WHERE d.type=3 AND a.order_stock_status=1 AND a.supply_closed_status=1 AND g.bom_ref_no != '' GROUP BY a.appr_item_code,b.id,g.bom_ref_no ORDER BY a.log DESC ";
        
            $result = $this->db->query($sql)->result_array();
        $k=0;
        foreach($result as $key => $value) {
            $tot_qty = 0;
            $total_issue_qty = 0;
            if($value['mi_bom_details_id'] != '') {
                $ref_sql = "SELECT * FROM tbl_mi_issued_details WHERE mi_bom_details_id = ".$value['mi_bom_details_id']." ";
                $ref_data = $this->db->query($ref_sql)->result_array();
            
                $bom_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_details_id = ".$value['mi_bom_details_id']." ";
                $ind_qty = $this->db->query($bom_sql)->row()->ind_qty;
                $tot_qty =$ind_qty;
                foreach ($ref_data as $key1 => $res1) {
                    $total_issue_qty += $res1['issued_qty'];
                }
            }
            
            if($tot_qty == $total_issue_qty) {
                $show_status = 'Yes';
                $status = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED-FULL</strong></span>';
            } else if($total_issue_qty > 0) {
                $show_status = 'No';
                $status = '<span class="text-light knBlueColor bg-dark"><strong>ISSUED-PART</strong></span>';
            } else {
                $show_status = 'No';
                $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
            }
            
            if($show_status == 'Yes') {
            $code = $value['appr_item_code'];
            $enq_id = base64_encode($value['id']);
            $req_id = base64_encode($value['request_ids']);
            $item_code = base64_encode($value['appr_item_code']);
            $pId = base64_encode($value['purchase_indent_id']);
            
            $item_codess = '<a class="bold" href="'.base_url().'request/Bomrequest/orderstockdetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value['appr_item_code'].'</a>';
            $res[$k]['isriorcode'] = $value['isriorcode'];
            $res[$k]['brandname'] = $value['brandname'];
            $res[$k]['item_desc'] = $value['item_desc'];
            $res[$k]['garment_size'] = $value['garment_size'];
            $res[$k]['appr_item_code'] = $item_codess;
            $res[$k]['appr_item_color_code'] = $value['appr_item_color_code'];
            $res[$k]['size_dim'] = $value['size_dim'];
            $res[$k]['uom'] = $value['uom'];
            $res[$k]['received_qtys'] = $value['received_qtys'];
            $res[$k]['received_uom'] = $value['received_uom'];
            $res[$k]['bom_ref_no'] = $value['bom_ref_no'];
            $res[$k]['bom_cutoff_date'] = $value['bom_cutoff_date'];
            $res[$k]['item_status'] = $status;

            $res[$k]['log'] = date('d-m-Y h:i A',strtotime($value['log']));
            $k++;
            }
            
        }
        
        return $res;
        
    }
    
   
    public function getOrderStockListt_old()
    {

        $res=[];
        $result = $bom_ref_no= $bom= $bom_cutoff=  $itemstat= $log1 = $log = $flag = $request_id = [];
        
        
        $enqsql = "SELECT a.appr_item_code,b.isriorcode,b.id,d.flag as flags3,d.enquiry_id,c.brandname FROM tbl_bom_in_house as a
                INNER JOIN tbl_purchase_indent as f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN tbl_request as d on f.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                WHERE d.type IN (3, 4) AND order_stock_status=1 AND a.order_closure_status_moved1 = 0  and d.subscriberid = " . $this->db->escape($this->subscriberId). " GROUP BY b.id";
        $enqresult = $this->db->query($enqsql)->result_array();
         
        foreach($enqresult as $key => $value) {
            $count = 0;
            $count2 =0;
            $enquiry_id = $value['id'];
            $item_desc = $bcm = $garment_size = $appr_item_code = $appr_item_color_code = $size_dim = $uom = $qty = $received_uom = $bom_status = $log = [];
            //$items = $this->get_firstChild($enquiry_id);
            
            $sql = "SELECT a.*, a.flag as flags2, a.request_id as request_ids, a.received_qty as received_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.item_received_status as irs, j.mi_bom_details_id FROM tbl_bom_in_house as a
                INNER JOIN tbl_request as d on a.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_bom_details as i on a.appr_item_code = i.item_code AND i.item_received_status = 0
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                LEFT JOIN tbl_mi_bom as h on i.mi_bom_id = h.mi_bom_id
                LEFT JOIN tbl_mi_details as g on h.request_id = g.request_id
                WHERE d.type IN (3, 4) AND order_stock_status=1 AND a.supply_closed_status_moved = 0   and a.order_closure_status_moved1=0 AND b.id= $enquiry_id  GROUP BY a.item_desc,a.garment_size,a.appr_item_code,a.appr_item_color_code,a.size_dim,a.uom  ORDER BY e.request_bom_id";
            $result = $this->db->query($sql)->result_array();
           // print_r($sql); 

        
            
            foreach($result as $key1 => $value1) {
                
                $enq_id = base64_encode($enquiry_id);
                $req_id = base64_encode($value1['request_ids']);
                $required_id = ($value1['request_id']);

                if($req_id== '' && $req_id == null) {
                    $req_id = base64_encode($required_id);  
                }

               
                $item_code = base64_encode($value1['appr_item_code']);
                
                $pId = base64_encode($value1['purchase_indent_id']);
                
                $code = $value1['appr_item_code'];
                $mi_sql1 = "SELECT c.item_code,d.bom_ref_no,c.mi_bom_details_id,c.ind_qty FROM tbl_mi_bom a 
                    INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
                    INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
                    INNER JOIN tbl_request e ON a.request_id = e.request_id
                    WHERE c.item_code = "."'$code'"."  AND  a.enquiry_id = "."'$enquiry_id'"."  AND e.deprt_approval = 1  ";
            $mi_data1 = $this->db->query($mi_sql1)->result_array();
            //PRINT_R($code);
            //print_r($mi_sql1); exit;
             $bom_status1 = [];
            if($mi_data1) {
                $flagss = ($value1['flags2']);
                $bom_id = $bom_qty = [];
                 
                //print_r($mi_data1);
               
                foreach($mi_data1 as $mkey => $mvalue) {
                    //$bom_status1 = [];
                    $bom_id[$mvalue['bom_ref_no']][] = $mvalue['mi_bom_details_id'];
                    $bom_qty[$mvalue['bom_ref_no']][] = $mvalue['ind_qty'];

                    // if($item_code = $mvalue['item_code']) {
                    //      $bom_status1[] = 'NIL';
                    // }
                    
                    
                }
                
                foreach($bom_id as $bkey => $bval) {
                    $bom_ind_qty[$bkey] = $bom_issue[$bkey] = [];
                    // print_r($bval);
                    $total_issue_qty = 0;
                    $tot_qty = 0;
                    for($l=0;$l<count($bval);$l++) {
                        $ref_sql = "SELECT * FROM tbl_mi_issued_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ref_data = $this->db->query($ref_sql)->result_array();
                        
                        $bom_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ind_qty = $this->db->query($bom_sql)->row()->ind_qty;
                        $tot_qty +=$ind_qty;
                        foreach ($ref_data as $key2 => $res2) {
                            $total_issue_qty += (int)$res2['issued_qty'];
                        }
                    }
                    
                    $bom_ind_qty[$bkey][] = $tot_qty;
                    $bom_issue[$bkey][] = $total_issue_qty;

                    //PRINT_R($bom_ind_qty);
                    
                    if($tot_qty == $total_issue_qty) {
                        //$status = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED-FULL</strong></span>';
                        $status = 'ISSUED-FULL';
                    } else if($total_issue_qty > 0) {
                        //$status = '<span class="text-light knBlueColor bg-dark"><strong>ISSUED-PART</strong></span>';
                        $status = 'ISSUED-PART';
                    } 
                    
                    else {
                        //$status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                        $status = 'PENDING';
                    }
                    
                    $bom_status1[] = $status;
                }

                //PRINT_R($bom_status1);
                    
            } else {
                $bom_status1[] = 'NIL';
            }
             //print_r($bom_status1); 
                $statusCheck = in_array('PENDING',$bom_status1);
                $statusCheck1 = in_array('ISSUED-PART',$bom_status1);
                $statusCheck2 = in_array('NIL',$bom_status1);
               
                if($statusCheck == '1' && $statusCheck1 == '0') {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">PENDING</span>';
                } else if($statusCheck == '0' && $statusCheck1 == '1') {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">ISSUED-PART</span>';
                } else if($statusCheck == '1' && $statusCheck1 == '1') {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">PENDING</span>';
                }  else if($statusCheck2 == '1' ) {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">-</span>';
                } 
                else {
                    $statusNew = '<span class="text-light knGreenColor bg-dark">ISSUED-FULL</span>';
                }

          $this->db->select_sum('a.received_qty', 'total_qtys')
         ->from('tbl_bom_in_house a')
         ->where('a.appr_item_code', $code)
         ->where('a.enquiryid', $enquiry_id);
         $received_qtys = $this->db->get()->row('total_qtys');

         

     $this->db->select('SUM(a.received_qty) AS total_qty,
    SUM(d.issued_qty) AS issued_qty,
    SUM(d.return_defective_qty) AS return_defective_qty,
    SUM(d.replace_defective_qty) AS replace_defective_qty,
    SUM(d.return_excess_qty) AS return_excess_qty');

    $this->db->from('tbl_bom_in_house a');
    $this->db->join('tbl_request b', 'a.request_id = b.request_id');
    $this->db->join('tbl_purchase_indent c', 'a.purchase_indent_id = c.purchase_indent_id');
    $this->db->join('tbl_mi_issued_details d', 'd.lot_no = a.bom_in_house_id');
    $this->db->where('a.order_stock_status', 1);
    $this->db->where('a.appr_item_code', $code);
    $this->db->where('a.enquiryid', $enquiry_id);
    $query = $this->db->get();
    $result = $query->row();

      $received_qty = (float)$result->total_qty;
      $issued_qty = (float)$result->issued_qty;
      $def_qty = (float)$result->return_defective_qty;
      $rdef_qty = (float)$result->replace_defective_qty;
      $excess_qty = (float)$result->return_excess_qty;


       $total_qty = $received_qtys - $issued_qty  - $rdef_qty + $excess_qty;
                    



       

                $item_desc[] = $value1['item_desc'];
                $bcm[] = $value1['bcm'];
                $garment_size[] = $value1['garment_size'];
                $appr_item_code[] = '<a class="bold" href="'.base_url().'request/Bomrequest/orderstockdetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value1['appr_item_code'].'</a>';
                $appr_item_color_code[] = $value1['appr_item_color_code'];
                $size_dim[] = $value1['size_dim'];
                $uom[] = $value1['uom'];
                //$qty[] = $value1['received_qtys'];
                  $qty[] = $total_qty;
                $bom_status[] = $statusNew;
                $received_uom[] = $value1['received_uom'];
                $logg = date('d/m/Y h:i A',strtotime($value1['log']));
                $log[] = $logg;
                $flag[] = $value1['flag'];
            }

            
            // $res[$key]['request_id'] = $required_id;
            // $res[$key]['isriorcode'] = $value['isriorcode'];
            // $res[$key]['brandname'] = $value['brandname'];
            // $res[$key]['item_desc'] = implode('<br />',$item_desc);
            // $res[$key]['bcm'] = implode('<br />',$bcm);
            // $res[$key]['garment_size'] = implode('<br />',$garment_size);
            // $res[$key]['appr_item_code'] = implode('<br />',$appr_item_code);
            // $res[$key]['appr_item_color_code'] = implode('<br />',$appr_item_color_code);
            // $res[$key]['size_dim'] = implode('<br />',$size_dim);
            // $res[$key]['uom'] = implode('<br />',$uom);
            // $res[$key]['received_qtys'] = implode('<br />',$qty);
            // $res[$key]['received_uom'] = implode('<br />',$received_uom);
            // $res[$key]['item_status'] = implode('<br />',$bom_status);
            // $res[$key]['log'] = implode('<br />',$log);
            // $res[$key]['flag'] = $flag;

         //$res[$key]['request_id'] = $required_id;
          $res[$key]['request_id'] = $value['enquiry_id'];
         $res[$key]['isriorcode'] = $value['isriorcode'];
         $res[$key]['brandname'] = $value['brandname'];
         $res[$key]['item_desc'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $item_desc);
         $res[$key]['bcm'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $bcm);
         $res[$key]['garment_size'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $garment_size);
         $res[$key]['appr_item_code'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $appr_item_code);
         $res[$key]['appr_item_color_code'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $appr_item_color_code);
         $res[$key]['size_dim'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $size_dim);
         $res[$key]['uom'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $uom);
         $res[$key]['received_qtys'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $qty);
         $res[$key]['received_uom'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $received_uom);
         $res[$key]['item_status'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $bom_status);
         $res[$key]['log'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $log);
         $res[$key]['flag'] = $value['flags3'];
            
            
        }
        
        return $res;
        
    }

    public function getOrderStockListt_oldBOM1()
    {
        $result = $bom_ref_no= $bom= $bom_cutoff=  $itemstat= $log1 = [];
          $res=[];
        
        
        $enqsql = "SELECT a.appr_item_code,b.isriorcode,b.id,d.flag as flags3,c.brandname FROM tbl_bom_in_house as a
                INNER JOIN tbl_purchase_indent as f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN tbl_request as d on f.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                WHERE d.type IN (3) AND order_stock_status=1  and a.order_closure_status_moved1 = 0 and d.subscriberid = " . $this->db->escape($this->subscriberId). " GROUP BY b.id";
        $enqresult = $this->db->query($enqsql)->result_array();
         
        foreach($enqresult as $key => $value) {
            $count = 0;
            $count2 =0;
            $enquiry_id = $value['id'];
            $item_desc = $bcm = $garment_size = $appr_item_code = $appr_item_color_code = $size_dim = $uom = $qty = $received_uom = $bom_status = $log = [];
            //$items = $this->get_firstChild($enquiry_id);
            
            $sql = "SELECT a.*, a.flag as flags2, g.request_id as request_ids, a.received_qty as received_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.item_received_status as irs, j.mi_bom_details_id FROM tbl_bom_in_house as a
                INNER JOIN tbl_request as d on a.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_bom_details as i on a.appr_item_code = i.item_code AND i.item_received_status = 0
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                LEFT JOIN tbl_mi_bom as h on i.mi_bom_id = h.mi_bom_id
                LEFT JOIN tbl_mi_details as g on h.request_id = g.request_id
                WHERE d.type IN (3) AND order_stock_status=1 and a.order_closure_status_moved1=0  AND b.id= $enquiry_id  GROUP BY a.item_desc,a.garment_size,a.appr_item_code,a.appr_item_color_code,a.size_dim,a.uom ORDER BY e.request_bom_id";
            $result = $this->db->query($sql)->result_array();
            //print_r($sql); 

        
            
            foreach($result as $key1 => $value1) {
                
                $enq_id = base64_encode($enquiry_id);
                $req_id = base64_encode($value1['request_ids']);
                $required_id = ($value1['request_id']);

                if($req_id== '' && $req_id == null) {
                    $req_id = base64_encode($required_id);  
                }

               
                $item_code = base64_encode($value1['appr_item_code']);
                $pId = base64_encode($value1['purchase_indent_id']);
                
                $code = $value1['appr_item_code'];
                $mi_sql1 = "SELECT d.bom_ref_no,c.mi_bom_details_id,c.ind_qty FROM tbl_mi_bom a 
                    INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
                    INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
                    INNER JOIN tbl_request e ON a.request_id = e.request_id
                    WHERE c.item_code = "."'$code'"." AND a.enquiry_id = "."'$enquiry_id'"."  AND e.deprt_approval = 1  ";
            $mi_data1 = $this->db->query($mi_sql1)->result_array();
            $bom_status1 = [];
            if($mi_data1) {
                $flagss = ($value1['flags2']);
                $bom_id = $bom_qty = [];
                foreach($mi_data1 as $mkey => $mvalue) {
                  
                    $bom_id[$mvalue['bom_ref_no']][] = $mvalue['mi_bom_details_id'];
                    $bom_qty[$mvalue['bom_ref_no']][] = $mvalue['ind_qty'];
                }
                
                foreach($bom_id as $bkey => $bval) {
                    $bom_ind_qty[$bkey] = $bom_issue[$bkey] = [];
                    // print_r($bval);
                    $total_issue_qty = 0;
                    $tot_qty = 0;
                    for($l=0;$l<count($bval);$l++) {
                        $ref_sql = "SELECT * FROM tbl_mi_issued_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ref_data = $this->db->query($ref_sql)->result_array();
                        
                        $bom_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ind_qty = $this->db->query($bom_sql)->row()->ind_qty;
                        $tot_qty +=$ind_qty;
                        foreach ($ref_data as $key2 => $res2) {
                            $total_issue_qty += (int)$res2['issued_qty'];
                        }
                    }
                    
                    $bom_ind_qty[$bkey][] = $tot_qty;
                    $bom_issue[$bkey][] = $total_issue_qty;
                    
                    if($tot_qty == $total_issue_qty) {
                        //$status = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED-FULL</strong></span>';
                        $status = 'ISSUED-FULL';
                    } else if($total_issue_qty > 0) {
                        //$status = '<span class="text-light knBlueColor bg-dark"><strong>ISSUED-PART</strong></span>';
                        $status = 'ISSUED-PART';
                    } else {
                        //$status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                        $status = 'PENDING';
                    }
                    
                    $bom_status1[] = $status;
                }
                    
            } else {
                $bom_status1[] = 'NIL';
            }
             //print_r($bom_status1); 
                $statusCheck = in_array('PENDING',$bom_status1);
                $statusCheck1 = in_array('ISSUED-PART',$bom_status1);
                $statusCheck2 = in_array('NIL',$bom_status1);
               
                if($statusCheck == '1' && $statusCheck1 == '0') {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">PENDING</span>';
                } else if($statusCheck == '0' && $statusCheck1 == '1') {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">ISSUED-PART</span>';
                } else if($statusCheck == '1' && $statusCheck1 == '1') {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">PENDING</span>';
                } else if($statusCheck2 == '1' ) {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">-</span>';
                }
                else {
                    $statusNew = '<span class="text-light knGreenColor bg-dark">ISSUED-FULL</span>';
                }

                $this->db->select_sum('a.received_qty', 'total_qtys')
         ->from('tbl_bom_in_house a')
         ->where('a.appr_item_code', $code)
         ->where('a.enquiryid', $enquiry_id);
         $received_qtys = $this->db->get()->row('total_qtys');

         

     $this->db->select('SUM(a.received_qty) AS total_qty,
    SUM(d.issued_qty) AS issued_qty,
    SUM(d.return_defective_qty) AS return_defective_qty,
    SUM(d.replace_defective_qty) AS replace_defective_qty,
    SUM(d.return_excess_qty) AS return_excess_qty');

    $this->db->from('tbl_bom_in_house a');
    $this->db->join('tbl_request b', 'a.request_id = b.request_id');
    $this->db->join('tbl_purchase_indent c', 'a.purchase_indent_id = c.purchase_indent_id');
    $this->db->join('tbl_mi_issued_details d', 'd.lot_no = a.bom_in_house_id');
    $this->db->where('a.order_stock_status', 1);
    $this->db->where('a.appr_item_code', $code);
    $this->db->where('a.enquiryid', $enquiry_id);
    $query = $this->db->get();
    $result = $query->row();

      $received_qty = (float)$result->total_qty;
      $issued_qty = (float)$result->issued_qty;
      $def_qty = (float)$result->return_defective_qty;
      $rdef_qty = (float)$result->replace_defective_qty;
      $excess_qty = (float)$result->return_excess_qty;


       $total_qty = $received_qtys - $issued_qty  - $rdef_qty + $excess_qty;
                    
                $item_desc[] = $value1['item_desc'];
                $bcm[] = $value1['bcm'];
                $garment_size[] = $value1['garment_size'];
                $appr_item_code[] = '<a class="bold" href="'.base_url().'request/Bomrequest/orderstockdetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value1['appr_item_code'].'</a>';
                $appr_item_color_code[] = $value1['appr_item_color_code'];
                $size_dim[] = $value1['size_dim'];
                $uom[] = $value1['uom'];
                //$qty[] = $value1['received_qtys'];
                $qty[] = $total_qty;
              
                $bom_status[] = $statusNew;
                $received_uom[] = $value1['received_uom'];
                $logg = date('d/m/Y h:i A',strtotime($value1['log']));
                $log[] = $logg;
                $flag[] = $value1['flag'];
            }
            
            
         $res[$key]['request_id'] = $required_id;
         $res[$key]['isriorcode'] = $value['isriorcode'];
         $res[$key]['brandname'] = $value['brandname'];
         $res[$key]['item_desc'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $item_desc);
         $res[$key]['bcm'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $bcm);
         $res[$key]['garment_size'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $garment_size);
         $res[$key]['appr_item_code'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $appr_item_code);
         $res[$key]['appr_item_color_code'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $appr_item_color_code);
         $res[$key]['size_dim'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $size_dim);
         $res[$key]['uom'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $uom);
         $res[$key]['received_qtys'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $qty);
         $res[$key]['received_uom'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $received_uom);
         $res[$key]['item_status'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $bom_status);
         $res[$key]['log'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $log);
         $res[$key]['flag'] = $value['flags3'];
            
            
        }
        
        return $res;
        
    }

    public function getOrderStockListt_oldBOM2()
    {
        $result = $bom_ref_no= $bom= $bom_cutoff=  $itemstat= $log1 = [];
          $res=[];
        
        
        $enqsql = "SELECT a.appr_item_code,b.isriorcode,b.id,d.flag as flags3,c.brandname FROM tbl_bom_in_house as a
                INNER JOIN tbl_purchase_indent as f on a.purchase_indent_id=f.purchase_indent_id
                INNER JOIN tbl_request as d on f.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                WHERE d.type IN (4) AND order_stock_status=1  and a.order_closure_status_moved1 = 0 and d.subscriberid = " . $this->db->escape($this->subscriberId). " GROUP BY b.id";
        $enqresult = $this->db->query($enqsql)->result_array();
         
        foreach($enqresult as $key => $value) {
            $count = 0;
            $count2 =0;
            $enquiry_id = $value['id'];
            $item_desc = $bcm = $garment_size = $appr_item_code = $appr_item_color_code = $size_dim = $uom = $qty = $received_uom = $bom_status = $log = [];
            //$items = $this->get_firstChild($enquiry_id);
            
            $sql = "SELECT a.*, a.flag as flags2, g.request_id as request_ids, a.received_qty as received_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.item_received_status as irs, j.mi_bom_details_id FROM tbl_bom_in_house as a
                INNER JOIN tbl_request as d on a.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_bom_details as i on a.appr_item_code = i.item_code AND i.item_received_status = 0
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                LEFT JOIN tbl_mi_bom as h on i.mi_bom_id = h.mi_bom_id
                LEFT JOIN tbl_mi_details as g on h.request_id = g.request_id
                WHERE d.type IN (4) AND order_stock_status=1 and a.order_closure_status_moved1=0 AND b.id= $enquiry_id  GROUP BY a.item_desc,a.garment_size,a.appr_item_code,a.appr_item_color_code,a.size_dim,a.uom ORDER BY e.request_bom_id";
            $result = $this->db->query($sql)->result_array();
            //print_r($sql); 

        
            
            foreach($result as $key1 => $value1) {
                
                $enq_id = base64_encode($enquiry_id);
                $req_id = base64_encode($value1['request_ids']);
                $required_id = ($value1['request_id']);

                if($req_id== '' && $req_id == null) {
                    $req_id = base64_encode($required_id);  
                }

               
                $item_code = base64_encode($value1['appr_item_code']);
                $pId = base64_encode($value1['purchase_indent_id']);
                
                $code = $value1['appr_item_code'];
                $mi_sql1 = "SELECT d.bom_ref_no,c.mi_bom_details_id,c.ind_qty FROM tbl_mi_bom a 
                    INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
                    INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
                    INNER JOIN tbl_request e ON a.request_id = e.request_id
                    WHERE c.item_code = "."'$code'"."  AND a.enquiry_id = ".$enquiry_id."   AND e.deprt_approval = 1  ";
            $mi_data1 = $this->db->query($mi_sql1)->result_array();
            //print_r($mi_sql1); exit;
            $bom_status1 = [];
            if($mi_data1) {
                $flagss = ($value1['flags2']);
                $bom_id = $bom_qty = [];
                foreach($mi_data1 as $mkey => $mvalue) {
                    //$bom_status1 = [];
                    $bom_id[$mvalue['bom_ref_no']][] = $mvalue['mi_bom_details_id'];
                    $bom_qty[$mvalue['bom_ref_no']][] = $mvalue['ind_qty'];
                }
                
                foreach($bom_id as $bkey => $bval) {
                    $bom_ind_qty[$bkey] = $bom_issue[$bkey] = [];
                    // print_r($bval);
                    $total_issue_qty = 0;
                    $tot_qty = 0;
                    for($l=0;$l<count($bval);$l++) {
                        $ref_sql = "SELECT * FROM tbl_mi_issued_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ref_data = $this->db->query($ref_sql)->result_array();
                        
                        $bom_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ind_qty = $this->db->query($bom_sql)->row()->ind_qty;
                        $tot_qty +=$ind_qty;
                        foreach ($ref_data as $key2 => $res2) {
                            $total_issue_qty += (int)$res2['issued_qty'];
                        }
                    }
                    
                    $bom_ind_qty[$bkey][] = $tot_qty;
                    $bom_issue[$bkey][] = $total_issue_qty;
                    
                    if($tot_qty == $total_issue_qty) {
                        //$status = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED-FULL</strong></span>';
                        $status = 'ISSUED-FULL';
                    } else if($total_issue_qty > 0) {
                        //$status = '<span class="text-light knBlueColor bg-dark"><strong>ISSUED-PART</strong></span>';
                        $status = 'ISSUED-PART';
                    } else {
                        //$status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                        $status = 'PENDING';
                    }
                    
                    $bom_status1[] = $status;
                }
                    
            } else {
                $bom_status1[] = 'NIL';
            }
             //print_r($bom_status1); 
                $statusCheck = in_array('PENDING',$bom_status1);
                $statusCheck1 = in_array('ISSUED-PART',$bom_status1);
                 $statusCheck2 = in_array('NIL',$bom_status1);
               
                if($statusCheck == '1' && $statusCheck1 == '0') {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">PENDING</span>';
                } else if($statusCheck == '0' && $statusCheck1 == '1') {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">ISSUED-PART</span>';
                } else if($statusCheck == '1' && $statusCheck1 == '1') {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">PENDING</span>';
                } else if($statusCheck2 == '1' ) {
                    $statusNew = '<span class="text-light knOrangeColor bg-dark">-</span>';
                } else {
                    $statusNew = '<span class="text-light knGreenColor bg-dark">ISSUED-FULL</span>';
                }

                $this->db->select_sum('a.received_qty', 'total_qtys')
         ->from('tbl_bom_in_house a')
         ->where('a.appr_item_code', $code)
         ->where('a.enquiryid', $enquiry_id);
         $received_qtys = $this->db->get()->row('total_qtys');

         

     $this->db->select('SUM(a.received_qty) AS total_qty,
    SUM(d.issued_qty) AS issued_qty,
    SUM(d.return_defective_qty) AS return_defective_qty,
    SUM(d.replace_defective_qty) AS replace_defective_qty,
    SUM(d.return_excess_qty) AS return_excess_qty');

    $this->db->from('tbl_bom_in_house a');
    $this->db->join('tbl_request b', 'a.request_id = b.request_id');
    $this->db->join('tbl_purchase_indent c', 'a.purchase_indent_id = c.purchase_indent_id');
    $this->db->join('tbl_mi_issued_details d', 'd.lot_no = a.bom_in_house_id');
    $this->db->where('a.order_stock_status', 1);
    $this->db->where('a.appr_item_code', $code);
    $this->db->where('a.enquiryid', $enquiry_id);
    $query = $this->db->get();
    $result = $query->row();

                $item_desc[] = $value1['item_desc'];
                $bcm[] = $value1['bcm'];
                $garment_size[] = $value1['garment_size'];
                $appr_item_code[] = '<a class="bold" href="'.base_url().'request/Bomrequest/orderstockdetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value1['appr_item_code'].'</a>';
                $appr_item_color_code[] = $value1['appr_item_color_code'];
                $size_dim[] = $value1['size_dim'];
                $uom[] = $value1['uom'];
                //$qty[] = $value1['received_qtys'];
                 $qty[] = $received_qtys;
                $bom_status[] = $statusNew;
                $received_uom[] = $value1['received_uom'];
                $logg = date('d/m/Y h:i A',strtotime($value1['log']));
                $log[] = $logg;
                $flag[] = $value1['flag'];
            }
             $res[$key]['request_id'] = $required_id;
         $res[$key]['isriorcode'] = $value['isriorcode'];
         $res[$key]['brandname'] = $value['brandname'];
         $res[$key]['item_desc'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $item_desc);
         $res[$key]['bcm'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $bcm);
         $res[$key]['garment_size'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $garment_size);
         $res[$key]['appr_item_code'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $appr_item_code);
         $res[$key]['appr_item_color_code'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $appr_item_color_code);
         $res[$key]['size_dim'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $size_dim);
         $res[$key]['uom'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $uom);
         $res[$key]['received_qtys'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $qty);
         $res[$key]['received_uom'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $received_uom);
         $res[$key]['item_status'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $bom_status);
         $res[$key]['log'] = implode('<span style="display:block; margin-bottom:10px;"></span>', $log);
         $res[$key]['flag'] = $value['flags3'];
            
            
        }
        
        return $res;
        
    }
    
    public function get_firstChild($enquiry_id)
    {
        $ress = [];
        
        $bcm = $garment_size = $appr_item_code = $appr_item_color_code = $size_dim = $uom = $received_qtys = $received_uom = $bom_no = $bom_date = $bom_status = $log_date =  [];
          $sql = "SELECT a.*, g.request_id as request_ids, SUM(a.received_qty) as received_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.item_received_status as irs FROM tbl_bom_in_house as a
                INNER JOIN tbl_request as d on a.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_bom_details as i on a.appr_item_code = i.item_code AND i.item_received_status = 0
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                LEFT JOIN tbl_mi_bom as h on i.mi_bom_id = h.mi_bom_id
                LEFT JOIN tbl_mi_details as g on h.request_id = g.request_id
                WHERE d.type=3 AND order_stock_status=1 AND a.supply_closed_status=1 AND b.id= $enquiry_id  GROUP BY a.item_desc,a.garment_size,a.appr_item_code,a.appr_item_color_code,a.size_dim,a.uom ORDER BY a.appr_item_code ASC";
            $result = $this->db->query($sql)->result_array();
            //print_r($sql); exit;
            foreach($result as $rkey => $value1) {
                $code = $value1['appr_item_code'];
                $enq_id = base64_encode($enquiry_id);
                $req_id = base64_encode($value1['request_ids']);
                $item_code = base64_encode($value1['appr_item_code']);
                $pId = base64_encode($value1['purchase_indent_id']);
                
                array_push($appr_item_code, '<a class="bold" href="'.base_url().'request/Bomrequest/orderstockdetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value1['appr_item_code'].'</a>');
                $item_desc[] = $value1['item_desc'];
                $bcm[] = $value1['bcm'];
                $garment_size[] = $value1['garment_size'];
                $appr_item_color_code[] = $value1['appr_item_color_code'];
                $size_dim[] = $value1['size_dim'];
                $uom[] = $value1['uom'];
                $received_qtys[] = $value1['received_qtys'];
                $received_uom[] = $value1['received_uom'];
                
                $mi_sql = "SELECT a.*,b.*,c.*,d.*  FROM tbl_mi_bom a 
                    INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
                    INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
                    INNER JOIN tbl_request e ON a.request_id = e.request_id
                    WHERE c.item_code = "."'$code'"." AND e.deprt_approval = 1 AND d.bom_ref_no != '' GROUP BY d.bom_ref_no,c.item_code ";
                $mi_data = $this->db->query($mi_sql)->result_array();
                // print_r($mi_sql); exit;
                if($mi_data) {
                 
                foreach($mi_data as $key2 => $value2) {
                    
                    if($value2['bom_ref_no'] != '') {
                        $miId = $value2['bom_ref_no'];
                    } else {
                        $miId = '-';
                    }
                    
                    if($value2['bom_cutoff_date'] != '') {
                        $cdate = $value2['bom_cutoff_date'];
                    } else {
                        $cdate = '-';
                    }
                    
                    if($value2['item_received_status'] != '') {
                        if($value2['item_received_status'] == 0) {
                            $status = '<span class="text-light knBlueColor">ISSUED-PART</span>';
                        } else if($value2['item_received_status'] == 1) {
                            $status = '<span class="text-light knGreenColor bg-dark">ISSUED-FULL</span>';
                        } else {
                            $status = '<span class="text-light knOrangeColor bg-dark">PENDING</span>';
                        }
                    } else {
                        $status = 'PENDING';
                    }
                    $bom_ref_no[$rkey][] = $miId;
                    $bom_no[] = $miId;
                    $bom_cutoff_date[$rkey][] = $cdate;
                    $bom_date[] = $cdate;
                    $item_status[$rkey][] = $status;
                    $bom_status[] = $status;
                    $log[$rkey][] = date('d-m-Y H:i:s A',strtotime($value2['log']));
                    $log_date[] = date('d-m-Y H:i:s A',strtotime($value2['log']));
                }
             } else {
                 $bom_ref_no[$rkey][] = '-';
                 $bom_no[] = '-';
                 $bom_cutoff_date[$rkey][] = '-';
                 $bom_date[] = '-';
                 $item_status[$rkey][] = '-';
                 $bom_status[] = '-';
                 $log[$rkey][] = '-';
                 $log_date[] = '-';
             }
             
             $mi_sql1 = "SELECT d.bom_ref_no,c.mi_bom_details_id,c.ind_qty FROM tbl_mi_bom a 
                    INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
                    INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
                    INNER JOIN tbl_request e ON a.request_id = e.request_id
                    WHERE c.item_code = "."'$code'"." AND e.deprt_approval = 1 AND d.bom_ref_no != ''  ";
            $mi_data1 = $this->db->query($mi_sql1)->result_array();
            //print_r($mi_data1); exit;
            if($mi_data1) {
                $bom_id = $bom_qty = [];
                foreach($mi_data1 as $mkey => $mvalue) {
                    
                    $bom_id[$code][] = $mvalue['mi_bom_details_id'];
                    $bom_qty[$code][] = $mvalue['ind_qty'];
                }
                
                foreach($bom_id as $bkey => $bval) {
                    $bom_ind_qty[$bkey] = $bom_issue[$bkey] = [];
                    // print_r($bval);
                    $total_issue_qty = 0;
                    $tot_qty = 0;
                    for($l=0;$l<count($bval);$l++) {
                        $ref_sql = "SELECT * FROM tbl_mi_issued_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ref_data = $this->db->query($ref_sql)->result_array();
                        
                        $bom_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ind_qty = $this->db->query($bom_sql)->row()->ind_qty;
                        $tot_qty +=$ind_qty;
                        foreach ($ref_data as $key => $res) {
                            $total_issue_qty += $res['issued_qty'];
                        }
                    }
                    
                    $bom_ind_qty[$bkey][] = $tot_qty;
                    $bom_issue[$bkey][] = $total_issue_qty;
                    
                    if($tot_qty == $total_issue_qty) {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED-FULL</strong></span>';
                    } else if($total_issue_qty > 0) {
                        $status = '<span class="text-light knBlueColor bg-dark"><strong>ISSUED-PART</strong></span>';
                    } else {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    
                    $bom_status1[] = $status;
                }
                
                    
            } else {
                $bom_status1[] = '-';
            }
                //print_r($bom_ind_qty); 
                //print_r($bom_id); 
                
                $ress['item_desc'] = $item_desc;
                $ress['bcm'] = $bcm;
                $ress['garment_size'] = $garment_size;
                $ress['appr_item_code'] = $appr_item_code;
                $ress['appr_item_color_code'] = $appr_item_color_code;
                $ress['size_dim'] = $size_dim;
                $ress['uom'] = $uom;
                $ress['received_qtys'] = $received_qtys;
                $ress['received_uom'] = $received_uom;
                $ress['bom_ref_no'] =  $bom_ref_no;
                $ress['bom_cutoff_date'] = $bom_cutoff_date;
                $ress['item_status'] =  $item_status;
                $ress['log'] = $log;
                $ress['bom_no'] =  $bom_no;
                $ress['bom_date'] =  $bom_date;
                $ress['bom_status'] =  $bom_status1;
                $ress['log_date'] =  $log_date;
                
             
            }
            
            return $ress;
        
    }
    
    public function get_firstChild_old($enquiry_id)
    {
        $ress = [];
        
        $bcm = $garment_size = $appr_item_code = $appr_item_color_code = $size_dim = $uom = $received_qtys = $received_uom = $bom_no = $bom_date = $bom_status = $log_date =  [];
          $sql = "SELECT a.*, g.request_id as request_ids, SUM(a.received_qty) as received_qtys, c.brandname,b.id, b.isriorcode, d.*, e.bcm,g.mi_id,g.bom_ref_no,g.bom_cutoff_date,i.item_received_status as irs FROM tbl_bom_in_house as a
                INNER JOIN tbl_request as d on a.request_id=d.request_id
                INNER JOIN kn_order_enquiry as b on d.enquiry_id=b.id
                INNER JOIN ".KN_MASTER_BRANDS." as c on b.brandId=c.id
                INNER JOIN tbl_request_bom as e on a.request_bom_id=e.request_bom_id
                LEFT JOIN tbl_mi_bom_details as i on a.appr_item_code = i.item_code AND i.item_received_status = 0
                LEFT JOIN tbl_mi_issued_details as j on i.mi_bom_details_id = j.mi_bom_details_id
                LEFT JOIN tbl_mi_bom as h on i.mi_bom_id = h.mi_bom_id
                LEFT JOIN tbl_mi_details as g on h.request_id = g.request_id
                WHERE d.type=3 AND order_stock_status=1 AND a.supply_closed_status=1 AND b.id= $enquiry_id  GROUP BY a.item_desc,a.garment_size,a.appr_item_code,a.appr_item_color_code,a.size_dim,a.uom ORDER BY a.appr_item_code ASC";
            $result = $this->db->query($sql)->result_array();
           // print_r($sql); exit;
            foreach($result as $rkey => $value1) {
                $code = $value1['appr_item_code'];
                $enq_id = base64_encode($enquiry_id);
                $req_id = base64_encode($value1['request_ids']);
                $item_code = base64_encode($value1['appr_item_code']);
                $pId = base64_encode($value1['purchase_indent_id']);
                
                array_push($appr_item_code, '<a class="bold" href="'.base_url().'request/Bomrequest/orderstockdetails/'.$enq_id.'/reqId/'.$req_id.'/itemCode/'.$item_code.'/pId/'.$pId.' " >'.$value1['appr_item_code'].'</a>');
                $item_desc[] = $value1['item_desc'];
                $bcm[] = $value1['bcm'];
                $garment_size[] = $value1['garment_size'];
                $appr_item_color_code[] = $value1['appr_item_color_code'];
                $size_dim[] = $value1['size_dim'];
                $uom[] = $value1['uom'];
                $received_qtys[] = $value1['received_qtys'];
                $received_uom[] = $value1['received_uom'];
                
                $mi_sql = "SELECT a.*,b.*,c.*,d.*  FROM tbl_mi_bom a 
                    INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
                    INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
                    INNER JOIN tbl_request e ON a.request_id = e.request_id
                    WHERE c.item_code = "."'$code'"." AND e.deprt_approval = 1 GROUP BY d.bom_ref_no ";
                $mi_data = $this->db->query($mi_sql)->result_array();
                // print_r($mi_sql); exit;
                if($mi_data) {
                 
                foreach($mi_data as $key2 => $value2) {
                    
                    if($value2['bom_ref_no'] != '') {
                        $miId = $value2['bom_ref_no'];
                    } else {
                        $miId = '-';
                    }
                    
                    if($value2['bom_cutoff_date'] != '') {
                        $cdate = $value2['bom_cutoff_date'];
                    } else {
                        $cdate = '-';
                    }
                    
                    if($value2['item_received_status'] != '') {
                        if($value2['item_received_status'] == 0) {
                            $status = '<span class="text-light knBlueColor">ISSUED-PART</span>';
                        } else if($value2['item_received_status'] == 1) {
                            $status = '<span class="text-light knGreenColor bg-dark">ISSUED-FULL</span>';
                        } else {
                            $status = '<span class="text-light knOrangeColor bg-dark">PENDING</span>';
                        }
                    } else {
                        $status = 'PENDING';
                    }
                    $bom_ref_no[$rkey][] = $miId;
                    $bom_no[] = $miId;
                    $bom_cutoff_date[$rkey][] = $cdate;
                    $bom_date[] = $cdate;
                    $item_status[$rkey][] = $status;
                    $bom_status[] = $status;
                    $log[$rkey][] = date('d-m-Y H:i:s A',strtotime($value2['log']));
                    $log_date[] = date('d-m-Y H:i:s A',strtotime($value2['log']));
                }
             } else {
                 $bom_ref_no[$rkey][] = '-';
                 $bom_no[] = '-';
                 $bom_cutoff_date[$rkey][] = '-';
                 $bom_date[] = '-';
                 $item_status[$rkey][] = '-';
                 $bom_status[] = '-';
                 $log[$rkey][] = '-';
                 $log_date[] = '-';
             }
             
             $mi_sql1 = "SELECT d.bom_ref_no,c.mi_bom_details_id,c.ind_qty FROM tbl_mi_bom a 
                    INNER JOIN tbl_sample_requirement b ON a.sample_req_id = b.sample_requirement_id
                    INNER JOIN tbl_mi_bom_details c ON a.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details d ON a.request_id = d.request_id
                    INNER JOIN tbl_request e ON a.request_id = e.request_id
                    WHERE c.item_code = "."'$code'"." AND e.deprt_approval = 1  ";
            $mi_data1 = $this->db->query($mi_sql1)->result_array();
            //print_r($mi_data1); exit;
            if($mi_data1) {
                $bom_id = $bom_qty = [];
                foreach($mi_data1 as $mkey => $mvalue) {
                    
                    $bom_id[$mvalue['bom_ref_no']][] = $mvalue['mi_bom_details_id'];
                    $bom_qty[$mvalue['bom_ref_no']][] = $mvalue['ind_qty'];
                }
                
                foreach($bom_id as $bkey => $bval) {
                    $bom_ind_qty[$bkey] = $bom_issue[$bkey] = [];
                    // print_r($bval);
                    $total_issue_qty = 0;
                    $tot_qty = 0;
                    for($l=0;$l<count($bval);$l++) {
                        $ref_sql = "SELECT * FROM tbl_mi_issued_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ref_data = $this->db->query($ref_sql)->result_array();
                        
                        $bom_sql = "SELECT * FROM tbl_mi_bom_details WHERE mi_bom_details_id = ".$bval[$l]." ";
                        $ind_qty = $this->db->query($bom_sql)->row()->ind_qty;
                        $tot_qty +=$ind_qty;
                        foreach ($ref_data as $key => $res) {
                            $total_issue_qty += $res['issued_qty'];
                        }
                    }
                    
                    $bom_ind_qty[$bkey][] = $tot_qty;
                    $bom_issue[$bkey][] = $total_issue_qty;
                    
                    if($tot_qty == $total_issue_qty) {
                        $status = '<span class="text-light knGreenColor bg-dark"><strong>ISSUED-FULL</strong></span>';
                    } else if($total_issue_qty > 0) {
                        $status = '<span class="text-light knBlueColor bg-dark"><strong>ISSUED-PART</strong></span>';
                    } else {
                        $status = '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                    }
                    
                    $bom_status1[] = $status;
                }
                
                    
            } else {
                $bom_status1[] = '-';
            }
                //print_r($bom_ind_qty); 
                //print_r($bom_id); 
                
                $ress['item_desc'] = $item_desc;
                $ress['bcm'] = $bcm;
                $ress['garment_size'] = $garment_size;
                $ress['appr_item_code'] = $appr_item_code;
                $ress['appr_item_color_code'] = $appr_item_color_code;
                $ress['size_dim'] = $size_dim;
                $ress['uom'] = $uom;
                $ress['received_qtys'] = $received_qtys;
                $ress['received_uom'] = $received_uom;
                $ress['bom_ref_no'] =  $bom_ref_no;
                $ress['bom_cutoff_date'] = $bom_cutoff_date;
                $ress['item_status'] =  $item_status;
                $ress['log'] = $log;
                $ress['bom_no'] =  $bom_no;
                $ress['bom_date'] =  $bom_date;
                $ress['bom_status'] =  $bom_status1;
                $ress['log_date'] =  $log_date;
                
             
            }
            
            return $ress;
        
    }
    
    

    public function getDClistt() {
    
        
        $sql = "SELECT a.*, a.log as logs,a.flag as flags,d.*, h.*, d.type as types, f.brandname, e.isriorcode, g.contactname as auth_name, a.item_received_status,a.mi_issue_id, b.issue_by,b.dc_ref_queue_no,b.dc_dt FROM tbl_mi_issued_details as a 
                    INNER JOIN tbl_mi_bom_details b ON a.mi_bom_details_id = b.mi_bom_details_id
                        INNER JOIN tbl_mi_bom c ON b.mi_bom_id = c.mi_bom_id
                    INNER JOIN tbl_mi_details as h on c.request_id=h.request_id
                    INNER JOIN tbl_request d ON h.request_id = d.request_id
                    INNER JOIN kn_order_enquiry as e on d.enquiry_id=e.id 
                    INNER JOIN ".KN_MASTER_BRANDS." as f on e.brandId=f.id 
                    INNER JOIN ".KN_USERS." as g on d.auth_by=g.id
                    WHERE a.dc_status=1 AND a.flag  IN (1,2)  and d.subscriberid = " . $this->db->escape($this->subscriberId)." GROUP BY a.dc_no ORDER BY a.log DESC";
        $result = $this->db->query($sql)->result_array();
    //print_r($result); exit;
        $mer_sql = "SELECT c.contactname as merchant_name
            FROM tbl_mi_details as a 
            INNER JOIN tbl_request as b on a.request_id=b.request_id
            INNER JOIN tbl_mi_bom as d on a.request_id=d.request_id
            INNER JOIN tbl_mi_bom_details as g on d.mi_bom_id=g.mi_bom_id
            INNER JOIN ".KN_USERS." as c on b.req_by=c.id
            WHERE a.dc_bom_status=1 AND a.flag IN (1,2) ORDER BY a.log DESC";
        $mer_result = $this->db->query($mer_sql)->result_array();

        $mer_sql2 = "SELECT c.contactname as sam_name
            FROM tbl_mi_details as a 
            INNER JOIN tbl_request as b on a.request_id=b.request_id
            INNER JOIN tbl_mi_bom as d on a.request_id=d.request_id
            INNER JOIN tbl_mi_bom_details as g on d.mi_bom_id=g.mi_bom_id
            INNER JOIN ".KN_USERS." as c on b.cad_by=c.id
            WHERE a.dc_bom_status=1 AND a.flag IN (1,2) ORDER BY a.log DESC";
        $mer_result2 = $this->db->query($mer_sql2)->result_array();

        $cad_result = [];
        // foreach ($result as $key => $value) {
        //     if($value['issue_by'] != '') {
        //         $cad_sql = "SELECT contactname as cad_name FROM kn_users WHERE id=".@$value['issue_by'];
        //         $cad_result = $this->db->query($cad_sql)->result_array();
        //     }

        //     $result[$key]['merchant_name'] = $mer_result[$key]['merchant_name'];
        //     $result[$key]['sam_name'] = @$mer_result2[$key]['sam_name'];
        //     $result[$key]['mi_issued_by'] = @$cad_result[0]['cad_name'];
        // }

        return $result;
    }

}