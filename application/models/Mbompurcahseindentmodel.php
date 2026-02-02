<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mbompurcahseindentmodel extends CI_Model {
    public function __construct() {
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
    }

    public function savePreparePi($VarSavingIssuePIDynamicTbl = array(),$VarTblName='',$VarBomPurReqId='') {
        $this->db->truncate($VarTblName);
        $VarDynamicTblRes = $this->saveBomPurchaseApprRequest($VarSavingIssuePIDynamicTbl, $VarTblName, $VarBomPurReqId);
        if ($VarDynamicTblRes) {
            $ArrResult['errcode'] = 1;
            $ArrResult['msg'] = '';
            return $ArrResult;
        }
    }

    public function saveBomPurchaseIndent($ArrBomPi=array(),$VarBomPurIndentId='') {
        if(empty($VarBomPurIndentId)) {
            $this->db->insert(KN_BOM_PURCHASEINDENT,$ArrBomPi);
            $VarId    							    = $this->db->insert_id();
            //$this->db->delete(KN_BOM_PI_APPROVAL_REQUEST);
            $this->db->truncate(KN_BOM_PI_APPROVAL_REQUEST);
        }
        else {
            $this->db->where('id',$VarBomPurIndentId);
            if($this->db->update(KN_BOM_PURCHASEINDENT,$ArrBomPi)) {
                $VarId							    = $VarBomPurIndentId;
            }
        }
        return $VarId;
    }

    public function getBomPIDetails($VarBomPurIndId = '',$VarBomPurReqId='',$BomPurIndentGridOnlyFlag='') {
        if(empty($BomPurIndentGridOnlyFlag)) {
            $this->db->select('pi.id,pi.orderid,purchasedeptid,pi.bompurrequestid,approvedbymgmtid,approvedDatetime,vendorid,purchaseindent_no,
            DATE_FORMAT(agreedsupplydate,"%d-%m-%Y %H:%i:%s") as agreedsupplydate, paymentterms,purchaseindgrid,taxtype,amountinwords,
            purdeptremarks,purchasername,purchasermobile,purchaseremail,xtravendorname,xtravendormobile,xtravendoremail,isriorcode,
            approvedstatus,advPaymentReqJxl,requirementforbom,i.bom_item_id');
            $this->db->where(array('pi.id'=>$VarBomPurIndId));
            $this->db->join(KN_ALLREQUEST .' AS a','a.id = pi.bompurrequestid');
            $this->db->join(BOMPI .' AS i','i.bompurchaseindentid = pi.id');
            $this->db->from(KN_BOM_PURCHASEINDENT .' AS pi');
            if($Res = $this->db->get()->result()) {
                return $Res[0];
            }
        }
        else {
            $this->db->select('pi.purchaseindgrid,taxtype');
            $this->db->where(array('pi.bompurrequestid'=>$VarBomPurReqId));
            $this->db->from(KN_BOM_PURCHASEINDENT .' AS pi');
            return $this->db->get()->result();
        }
    }
    
    public function getBomPIAdvPayment($VarBomPurIndId='') {
        $this->db->select('id,paymentpaidgrid');
        $this->db->from(KN_BOMPI_PAY_PAIDDETAILS .' AS pd');
        $this->db->where('pd.bompurindentid',$VarBomPurIndId);
        $Res = $this->db->get()->result();
        if(empty($Res))
            return false;
        else
            return $Res[0];
    }

    public function bomPurIndentListDatatablesAjax() {
        $this->bomPurIndentListDatatablesQry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function bomPurIndentListDatatablesQry() {
        //Only after mgmt approved thr PI
        $this->db->select('pi.id,a.isriorcode,v.vendorname,purchaseindent_no,vendorid,articletype,pi.status,
        DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") as cutoffdatetime,DATE_FORMAT(pi.dateupdated,"%d-%m-%Y %H:%i:%s") as dateupdated,
        approvedstatus,purchasedeptid,oe.isriorcode,DATE_FORMAT(pi.datecreated,"%d-%m-%Y %H:%i:%s") as datecreated,u.contactname,
        requirementforbom,queueno,m_u.contactname as approvedByMgmt');
        $this->db->from(KN_BOM_PURCHASEINDENT.' AS pi');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = pi.bompurrequestid');
        //$this->db->join(BOMPI.' AS i','pi.id = i.bomPurchaseRequestId');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->join(KN_MASTER_BOM_VENDOR.' AS v','v.id = pi.vendorid');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');

        $this->db->join(KN_USERS.' AS u','pi.purchasedeptid = u.id');
        $this->db->join(KN_USERS.' AS m_u','pi.approvedbymgmtid = m_u.id');

        $bomPiApprListColOrder = array('pi.dateupdated','oe.isriorcode', 'oe.brandbuyerid', 'a.id', 'a.requestdt', 'a.cutoffdatetime', 'a.merchantid',
            'a.approvaltype', 'a.mgmtid', 'a.dateupdated', 'a.status');
        $bomPiApprListColSearch = array('oe.isriorcode', 'oe.brandbuyerid', 'a.id', 'a.requestdt', 'a.cutoffdatetime', 'a.merchantid',
            'a.approvaltype', 'a.mgmtid', 'a.dateupdated', 'a.status');
        $bomPiApprListOrder = array('pi.dateupdated' => 'desc');

        $i = 0;
        foreach ($bomPiApprListColSearch as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($bomPiApprListColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($bomPiApprListColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order)) {
            $order = $bomPiApprListColOrder;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function countBomPurIndentListFiltered() {
        $this->bomPurIndentListDatatablesQry();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function countBomPurIndentListAll() {
        $this->db->from(KN_BOM_PURCHASEINDENT.' AS pi');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = pi.bompurrequestid');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->join(KN_MASTER_BOM_VENDOR.' AS v','v.id = pi.vendorid');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_USERS.' AS u','pi.purchasedeptid = u.id');
        $this->db->join(KN_USERS.' AS m_u','pi.approvedbymgmtid = m_u.id');
        return $this->db->count_all_results();
    }

    /*
     * For Finance Dept Advance Payment List
     * */
    public function bomPIAdvpayListDatatablesAjax() {
        $this->bomPIAdvpayListDatatablesQry();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function bomPIAdvpayListDatatablesQry() {
        $this->db->select('pi.id,pi.current_status,pi.approvedbymgmtid,a.isriorcode,v.vendorname,purchaseindent_no,vendorid,articletype,pi.status,
        DATE_FORMAT(cutoffdatetime,"%d-%m-%Y %H:%i:%s") as cutoffdatetime,DATE_FORMAT(pi.dateupdated,"%d-%m-%Y %H:%i:%s") as dateupdated,
        approvedstatus,purchasedeptid,oe.isriorcode,brandname,DATE_FORMAT(pi.datecreated,"%d-%m-%Y %H:%i:%s") as datecreated,
        u.contactname,m_u.contactname as approvedByMgmt');
        $this->db->from(KN_BOM_PURCHASEINDENT.' AS pi');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = pi.bompurrequestid');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->join(KN_MASTER_BOM_VENDOR.' AS v','v.id = pi.vendorid');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','pi.purchasedeptid = u.id');
        $this->db->join(KN_USERS.' AS m_u','pi.approvedbymgmtid = m_u.id');

        $bomPiApprListColOrder = array('pi.dateupdated','oe.isriorcode', 'oe.brandbuyerid', 'a.id', 'a.requestdt', 'a.cutoffdatetime', 'a.merchantid',
            'a.approvaltype', 'a.mgmtid', 'a.dateupdated', 'a.status');
        $bomPiApprListColSearch = array('oe.isriorcode', 'oe.brandbuyerid', 'a.id', 'a.requestdt', 'a.cutoffdatetime', 'a.merchantid',
            'a.approvaltype', 'a.mgmtid', 'a.dateupdated', 'a.status');
        $bomPiApprListOrder = array('pi.dateupdated' => 'desc');

        $i = 0;
        foreach ($bomPiApprListColSearch as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($bomPiApprListColSearch) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($bomPiApprListColOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order)) {
            $order = $bomPiApprListColOrder;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    public function countBomPIAdvpayListFiltered() {
        $this->bomPurIndentListDatatablesQry();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function countBomPIAdvPayListAll() {
        $this->db->from(KN_BOM_PURCHASEINDENT.' AS pi');
        $this->db->join(KN_ALLREQUEST.' AS a','a.id = pi.bompurrequestid');
        $this->db->where('a.companyid',$this->companyid);
        $this->db->join(KN_MASTER_BOM_VENDOR.' AS v','v.id = pi.vendorid');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','a.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
        $this->db->join(KN_USERS.' AS u','pi.purchasedeptid = u.id');
        $this->db->join(KN_USERS.' AS m_u','pi.approvedbymgmtid = m_u.id');
        return $this->db->count_all_results();
    }

    public function saveBomPurIndPayPaidDetails($ArrAdvPaidData,$VarBomPurIndId) {
        $this->db->where('bompurindentid',$VarBomPurIndId);
        $query = $this->db->get(KN_BOMPI_PAY_PAIDDETAILS);
        if($query->num_rows() == 1) {
            $this->db->where('bompurindentid',$VarBomPurIndId);
            $this->db->update(KN_BOMPI_PAY_PAIDDETAILS, $ArrAdvPaidData);
            return $this->db->affected_rows();
        }
        else {
            $this->db->insert(KN_BOMPI_PAY_PAIDDETAILS, $ArrAdvPaidData);
            return $this->db->insert_id();
        }
    }

    /**Save in Dynamic Table and insert table name in KN_BOMPURCHASEREQ as bompirequestgrid_tblname*/
    public function createBomPurIndApprRequestTbl($ArrUpdateData = array(), $VarTblName,$VarBomPurReqId) {
        if (empty($VarTblName)) {
            $VarGeneratedTblName = 'bompi_' . mt_rand() . '_' . time();
            $VarSql = "CREATE TABLE IF NOT EXISTS $VarGeneratedTblName (
         `id` bigint NOT NULL AUTO_INCREMENT,
         `companyid` bigint NOT NULL,
         `orderid` bigint NOT NULL,
         `bomPurchaseRequestId` bigint NOT NULL,             
         `bomPurchaseIndentId` bigint NOT NULL,             
         `itemdesc` varchar(100) NOT NULL,
         `garmentsize` varchar(5) NOT NULL,
         `itemcode` varchar(60) NOT NULL,
         `itemcolorcode` varchar(60) NOT NULL,
         `sizeordim` varchar(10) NOT NULL,
         `uom1` varchar(15) NOT NULL,
         `planbomqty` varchar(10) NOT NULL,
         `progbomqty` varchar(10) NOT NULL,             
         `uom2` varchar(15) NOT NULL,        
         `currency` varchar(4) NOT NULL,
         `unitrate` varchar(4) NOT NULL,
         `amount` varchar(10) NOT NULL,
         `sgstpercent` tinyint(4) NOT NULL,
         `sgstvalue` varchar(4) NOT NULL,
         `cgstpercent` tinyint(4) NOT NULL,
         `cgstvalue` varchar(4) NOT NULL,    
         `igstpercent` tinyint(4) NOT NULL,
         `igstvalue` varchar(4) NOT NULL,
         `dutypercent` tinyint(4) NOT NULL,
         `dutyvalue` varchar(4) NOT NULL,  
         `subtotal` varchar(10) NOT NULL,
         `tempselect` tinyint(4) NOT NULL,                              
         `hiddenstatus` tinyint(4) NOT NULL,   
         `selectcheckbox` tinyint(4) NOT NULL,
         `status` tinyint(4) NOT NULL,   
         PRIMARY KEY (`id`)                
        )";
            if (!$this->db->simple_query($VarSql)) {
                return $this->db->error();
            } else {
                return $this->saveDynamicTbl($ArrUpdateData, $VarGeneratedTblName, $VarBomPurReqId);
            }
        } else {
            $this->db->truncate($VarTblName);
            return $this->saveDynamicTbl($ArrUpdateData, $VarTblName);
        }
    }

    /*public function bomDataBilkInsert($ArrUpdateData = array(), $VarBomPurReqId) {
        $VarNumRowsIns = $this->db->insert_batch(KN_BOM_FOR_PI,$ArrUpdateData);
        if($VarNumRowsIns) {*/
            /*$this->db->select('bompirequestgrid_tblname');
            $Qry = $this->db->get_where(KN_BOMPURCHASEREQ, array('requestrefid' => $VarBomPurReqId));
            $VarCheckDynamicTbl = $Qry->row();
            if (empty($VarCheckDynamicTbl->bompirequestgrid_tblname)) {
                $this->db->where('requestrefid', $VarBomPurReqId);
                $this->db->update(KN_BOMPURCHASEREQ, array('bompirequestgrid_tblname' => $VarTblName));
            } else {

            }*/
            /*return $VarNumRowsIns;
        }
    }*/

    public function saveDynamicTbl($ArrUpdateData = array(),$VarTblName='',$VarBomPurReqId='') {
        $VarNumRowsIns = $this->db->insert_batch($VarTblName,$ArrUpdateData);
        if($VarNumRowsIns) {
            $this->db->select('bompirequestgrid_tblname');
            $Qry = $this->db->get_where(KN_BOMPURCHASEREQ, array('requestrefid' => $VarBomPurReqId));
            $VarCheckDynamicTbl = $Qry->row();
            if (empty($VarCheckDynamicTbl->bompirequestgrid_tblname)) {
                $this->db->where('requestrefid', $VarBomPurReqId);
                $this->db->update(KN_BOMPURCHASEREQ, array('bompirequestgrid_tblname' => $VarTblName));
            } else {

            }
            return $VarNumRowsIns;
        }
    }

    public function savePurchaseIndentDynamicTbl($VarTableName,$ArrUpdate,$VarId) {

        $this->db->where('id',$VarId);
        $this->db->update($VarTableName, $ArrUpdate);
    }

    /**
     * Get Data from dynamic table
     * for BOM Prepare PI page
     * @param $tempSelect is 1 for temporary select
    **/
    /*public function getBomPIDynamicTblData($tempSelect='',$hiddenStatus='',$BomRequestId='',$PurchaseIndentId='',$VarOrderId='',$VarStatus='',$bom_item_id='') {
        $ArrWhere = []; $VarWhere = '';
        $VarTblName = 'bompi';
        if (!empty($tempSelect)) {
            $ArrWhere[] = "tempselect = '$tempSelect'";
        }
        if(!empty($hiddenStatus)) {
            $ArrWhere[] = "hiddenstatus = '$hiddenStatus'";
        }
        if(!empty($BomRequestId)) {
            $ArrWhere[] = "bompurchasrerequestid = '$BomRequestId' ";
        }
        if(!empty($PurchaseIndentId)) {
            $ArrWhere[] = "bompurchaseindentid = '$PurchaseIndentId' ";
        }
        if(!empty($VarOrderId)) {
            $ArrWhere[] = "orderid = '$VarOrderId' ";
        }
        if(!empty($VarStatus)) {
            $ArrWhere[] = "status = '$VarStatus' ";
        }
        if(!empty($bom_item_id)) {
            $ArrWhere[] = "bom_item_id = '$bom_item_id' ";
        }
        if(count($ArrWhere) >= 1) {
            $VarWhere = " WHERE " . implode( " AND ",$ArrWhere);
        }
        $VarSql = "SELECT bompurchaseindentid,itemdesc,garmentsize,itemcode,itemcolorcode,sizeordim,uom1,planbomqty,progbomqty,uom2,
        currency,unitrate,amount,id,hiddenstatus FROM  ".$VarTblName . $VarWhere;
        return $this->db->query($VarSql)->result_array();
    }*/

    /*
     * Get Data from dynamic table
     * for BOM Prepare PI page
     * @param $tempSelect is 1 for temporary select
   */
    public function getbomPIDynamicTbldata($VarTblName='',$tempSelect='',$hiddenStatus='',$VarBomPurReqId='',$bomPurchaseIndentId='',$VarOrderId='') {
        $ArrWhere = []; $VarWhere = '';
        if (!empty($tempSelect)) {
            $ArrWhere[] = "tempselect = '$tempSelect'";
            //$VarSql = "SELECT * FROM " . $VarTblName." WHERE tempselect = ".$tempSelect;
        }
        if(!empty($hiddenStatus)) {
            $ArrWhere[] = "hiddenstatus = '$hiddenStatus'";
        }
        if(!empty($VarBomPurReqId)) {
            $ArrWhere[] = "bomPurchaseRequestId = '$VarBomPurReqId' ";
        }
        if(!empty($bomPurchaseIndentId)) {
            $ArrWhere[] = "bomPurchaseIndentId = '$bomPurchaseIndentId' ";
        }
        if(!empty($VarOrderId)) {
            $ArrWhere[] = "orderid = '$VarOrderId' ";
        }
        if(!empty($ArrWhere)) {
            $VarWhere = " WHERE " . implode( " AND ",$ArrWhere);
        }
        $VarSql = "SELECT * FROM  ".$VarTblName . $VarWhere;
        //echo '<pre>'; print_r($VarSql); die();
        return $this->db->query($VarSql)->result_array();
    }

    public function savePurchaseIndentFinal($ArrData = array(),$VarTblName,$ArrBomPiTblData = array()) {
        foreach ($ArrData as $dataItem) {
            $VarPrimaryId = $dataItem['id'];
            $this->db->where('id',$VarPrimaryId);
            unset($dataItem['id']);
            $this->db->update($VarTblName,$dataItem);
        }

        foreach ($ArrBomPiTblData as $item) {
            $this->db->insert('bompi',$item);
        }
        return true;
    }

    /*public function savePurchaseIndentFinal($ArrData = array(),$VarTblName) {
        foreach ($ArrData as $dataItem) {
            $VarPrimaryId = $dataItem['id'];
            $this->db->where('id',$VarPrimaryId);
            unset($dataItem['id']);
            $this->db->update($VarTblName,$dataItem);
        }
        return $this->db->affected_rows();
    }*/

    public function getPurchaseIndentNo($VarOrderId='') {
        return $this->db->select('purchaseindent_no')->where('orderid', $VarOrderId)->from(KN_BOM_PURCHASEINDENT)->get()->result();
    }

    public function newItemListDataTables() {
        $this->dataTables_newItemList_qry();
        $match = 0;
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();

        //echo '<pre>'; print_r($query->result()); die('die');
        //foreach ($query->result() as $resultData) {
            //$ArrMatchedkeys = $resultData->matchedkeys;
			//$bom_received_id_arr[] = implode(',', unserialize($resultData->bom_received_id_arr));
            //echo '<pre>'; print_r($ArrFromBomMerInd);
            //echo '<pre>'; print_r($resultData->bomgroup2send);
			//$ArrMatchedkeys = array_unique($ArrMatchedkeys);
        //}
		//$matchedkeys = explode(',',$ArrMatchedkeys);
		//echo '<br/>';
		//$bom_received_id_arr = array_unique($bom_received_id_arr);
		//echo '<pre>'; print_r(array_intersect($matchedkeys,$bom_received_id_arr)); die('die');
        //foreach ($query->result() as $resultData) {
            //    $match = 1;
                //echo '<pre>'; print_r($resultData->bomgroup2send);
                //echo '<pre>'; print_r($ArrFromBomMerInd);
                //echo '<pre>'; print_r(unserialize($resultData->bomindentby_merchant)); die('die');
        //}
            //return $query->result();
    }

    public function dataTables_newItemList_qry() {
        $column_order = array('isriorcode','brandname','bom','','','','','','','','new.datecreated','');
        $column_search = array('isriorcode','brandname','bom','','','','','','','','new.datecreated','');
        $order = array('new.dateupdated' => 'desc');

        $this->db->select('new.id,new.orderid,itemdesc,garmentsize,itemcode,itemcolorcode,sizeordim,uom1,bomreqdqty,planbomqty,uom2,
        purchaseIndentBomId,isriorcode,brandname,buyername,DATE_FORMAT(new.datecreated,"%d-%m-%Y %H:%i:%s") as date_created');
        $VarAllBomTbl = 'bom_companyid_'.$this->companyid;
        $this->db->from(KN_STORES_NEW_ITEM_LIST.' AS new');
        $this->db->join($VarAllBomTbl.' AS i', 'new.purchaseIndentBomId = i.id');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','new.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandbuyerid');
        // commented by myself  regards new brand form
       // $this->db->join(KN_MASTER_BUYER.' AS by','br.buyerid = by.id');
        $i = 0;
        foreach ($column_search as $item) {
            //echo '<pre>'; print_r($this->input->post('search')); die('die');
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }

    }

    public function count_filteredNewItemListDataTables() {
        $this->dataTables_newItemList_qry();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_allNewItemListDataTables() {
        $VarAllBomTbl = 'bom_companyid_'.$this->companyid;
        $this->db->from(KN_STORES_NEW_ITEM_LIST.' AS new');
        $this->db->join($VarAllBomTbl.' AS i', 'new.purchaseIndentBomId = i.id');
        $this->db->join(KN_ORDER_ENQUIRY.' AS oe','new.orderid = oe.id');
        $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandbuyerid');
        // commented by myself  regards new brand form
        //$this->db->join(KN_MASTER_BUYER.' AS by','br.buyerid = by.id');
		return $this->db->count_all_results();
    }

    public function getNewItemDetails($VarId,$VarCompanyId) {
        $this->db->select('bompurindentid');
        $VarQry    = $this->db->get_where(KN_STORES_NEW_ITEM_LIST, array('id' => $VarId,'companyid'=>$VarCompanyId));
        $ArrResult = $VarQry->row();
        return $ArrResult;
    }

}