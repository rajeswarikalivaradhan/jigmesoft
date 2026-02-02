<?php



/**

 * Created by PhpStorm.

 * User: Vignesh

 * Date: 07-07-2018

 * Time: 10:22 PM

 */

if (!defined('BASEPATH')) exit('No direct script access allowed');



class Cronmodel extends CI_Model

{



    public function chgExpOrders($VarDate, $VarPackageType, $VarUpdatedBy)

    {

        $VarUpdatedDate = date('Y-m-d H:i:s');

        $VarFreeSql = $this->db->query("SELECT p.id,price,originalprice,recurringtype,displayname,packagetype,numberofuser FROM KN_MASTER_PRICE AS p INNER JOIN 

        KN_MASTER_PRICE_CONF_PRICE AS pcf ON p.id = pcf.priceid WHERE isFree = 1 AND p.status = 1 AND packagetype = " . $VarPackageType);



        if ($VarFreeSql->num_rows() > 0) {

            $freePlanInfo = $VarFreeSql->row();

        } else $freePlanInfo = '';

        //$ArrWhere = array('expirydate' => $VarDate, 'status' => 1, 'packagetype' => $VarPackageType);

        //$this->db->update(KN_COMPANY_ORDERS, array('invoicestatus' => 3), $ArrWhere);

        //if($this->db->affected_rows() > 0) {

        //return 'in';

//                $VarIns = "INSERT INTO KN_COMPANY_ORDERS ($freePlanInfo->id, ordercode, companyid, ".$freePlanInfo->displayname.",

//                            ".$freePlanInfo->packagetype.", ".$freePlanInfo->recurringtype.", ".$freePlanInfo->numberofuser.",

//                            ".$freePlanInfo->price.",".$freePlanInfo->originalprice.", expirydate,3,orderstatus,status,updatedby,dateupdated) ";

//                $VarSql = $this->db->query($VarIns . "SELECT priceid,ordercode,companyid,displayname,packagetype,recurringtype,

//                    numberofuser,price,originalprice,expirydate,invoicestatus,orderstatus,status,updatedby,dateupdated FROM KN_COMPANY_ORDERS WHERE

//                    expirydate= '".$VarDate."' and status = 1 and packagetype = ".$VarPackageType);

//                echo 1;

        //}



        $this->db->select('id');

        $ArrFreeWhere = array('expirydate' => $VarDate, 'status' => 1, 'packagetype' => $VarPackageType, 'priceid' => $freePlanInfo->id);

        $ArrExpResSql = $this->db->get_where(KN_COMPANY_ORDERS, $ArrFreeWhere);

        if ($ArrExpResSql->num_rows() > 0) {

            $ArrExpRes = $ArrExpResSql->result();

            foreach ($ArrExpRes as $ArrExp) {

                //companyid,displayname,packagetype,recurringtype, numberofuser,price,originalprice,expirydate,invoicestatus,orderstatus,status,updatedby,dateupdated



                $this->db->where('id', $ArrExp->id);

                $this->db->update(KN_COMPANY_ORDERS, array('invoicestatus' => 3));



            }

        }

        $ArrNonFreWhere = array('expirydate' => $VarDate, 'status' => 1, 'packagetype' => $VarPackageType);

        $ArrNonFreeSql = $this->db->get_where(KN_COMPANY_ORDERS, $ArrNonFreWhere);

        if ($ArrNonFreeSql->num_rows() > 0) {

            $ArrNonFreeRes = $ArrNonFreeSql->result();

            foreach ($ArrNonFreeRes as $ArrNonFree) {

                $ArrIns = array('priceid' => $freePlanInfo->id, 'ordercode' => $ArrNonFree->ordercode, 'companyid' => $ArrNonFree->companyid, 'displayname' => $freePlanInfo->displayname, 'packagetype' => $freePlanInfo->packagetype, 'recurringtype' => $freePlanInfo->recurringtype, 'numberofuser' => $freePlanInfo->numberofuser, 'price' => $freePlanInfo->price, 'originalprice' => $freePlanInfo->originalprice, 'expirydate' => $ArrNonFree->expirydate, 'invoicestatus' => 3, 'orderstatus' => $ArrNonFree->orderstatus, 'status' => 1, 'updatedby' => $VarUpdatedBy, 'dateupdated' => $VarUpdatedDate);



                $this->db->insert(KN_COMPANY_ORDERS, $ArrIns);

            }





        }

        return true;

    }

   public function updatedays(){
       $currentdate=date('Y-m-d');
       $this->db->select("sl.id,sl.subscriber_id,sl.proforma_id,DATEDIFF(sl.subscriber_enddate,'$currentdate') as no_of_days_left")->from(KN_SUBSCRIBERLIST . ' AS sl')->where('sl.status', 1);
       return $this->db->get()->result();
   }
   public function updatesubscription(){
       $currentdate=date('Y-m-d');
       $this->db->select("sl.*")->from(KN_SUBSCRIBERLIST . ' AS sl')->where('sl.subscriber_enddate', "'$currentdate'");
       return $this->db->get()->result();
   }
    public function updatestandbyproforma($subscriber_id){
    $this->db->select("pi.*")->from(KN_PROFORMAINVOICE . ' AS pi')->where('pi.subscriber_id', "$subscriber_id")->where('pi.status', 3)->where('pi.paymentstatus', 2)->where('pi.invoice_status', 2)->where('pi.confirm_status', 1);
    return $this->db->get()->result();
    }
    public function getinactivesubscribers(){
        $this->db->distinct()->select("sl.subscriber_id,sl.subscriber_refno,sl.proforma_id")->from(KN_SUBSCRIBERLIST . ' AS sl')->where('sl.renewal_daysleft',0)->where('status', 2);
        return $this->db->get()->result();
    }
    public function getnoofdays($startdate,$enddate){
        $dateString1 = $startdate;
        $dateString2 = $enddate;
        
        // Create DateTime objects from the date strings
        $date1 = new DateTime($dateString1);
        $date2 = new DateTime($dateString2);
        
        // Calculate the difference between two dates
        $interval = $date1->diff($date2);
        
        // Get the number of days from the interval
        $numberOfDays = $interval->days;
        return $numberOfDays;
    }
}