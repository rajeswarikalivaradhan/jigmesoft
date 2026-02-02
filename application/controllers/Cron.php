<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function __construct() {
		parent::__construct();
	    $this->load->model('cronmodel');
      date_default_timezone_set('Asia/Kolkata');
	}

    public function updaterenewal() {
        
         $Arrresult = $this->cronmodel->updatedays();
         if(!empty($Arrresult)){
            foreach ($Arrresult as $k=>$v){
            $status=($v->no_of_days_left==0)?2:1;
            if($status==2){ // newly included to inactivate when subscribers noofdays_renewal is zero
              $this->db->where('subscriber_id', $v->subscriber_id)->where('proforma_id',  $v->proforma_id);
              $this->db->update(KN_MUSERS,array('status' => $status,'dateupdated'=>date('Y-m-d H:i:s')));      
              $this->db->where('id', $v->proforma_id)->update(KN_PROFORMAINVOICE,array('status' => $status,'dateupdated'=>date('Y-m-d H:i:s')));      
            }
            $this->db->where('id', $v->id);
            $this->db->update(KN_SUBSCRIBERLIST,array('renewal_daysleft' => $v->no_of_days_left,'status' => $status,'dateupdated'=>date('Y-m-d H:i:s')));        
           
          }
         }  
         log_message('info', 'Calling updaterenewal_pckgmigration()...');
         $this->updaterenewal_pckgmigration();
         log_message('info', 'updaterenewal_pckgmigration() executed successfully.');
    }
    public function updaterenewal_pckgmigration() {
      $Arrresult = $this->cronmodel->getinactivesubscribers();
      $Varstart_date = date('Y-m-d');
  
      if (!empty($Arrresult)) {
          foreach ($Arrresult as $k => $v) {
              $Varsubscriber_refno = $v->subscriber_refno;
  
              // Fetch standby proforma details
              $Arrresults = $this->cronmodel->updatestandbyproforma($v->subscriber_id);
  
              if (!empty($Arrresults)) {
                  foreach ($Arrresults as $kr => $vr) {
                      // Subscription period mapping
                      $subscriptionPeriods = [1 => 3, 2 => 6, 3 => 12];
                      $subscriptionperiod = $subscriptionPeriods[$vr->subscription_period] ?? 0;
  
                      // Date calculations using DateTime for accuracy
                      $Varend_date = (new DateTime($Varstart_date))
                                      ->modify("+$subscriptionperiod months")
                                      ->modify("-1 day")
                                      ->format('Y-m-d');
  
                      // Calculate renewal days left
                      $daysleftrenewal = $this->cronmodel->getnoofdays($Varstart_date, $Varend_date);
  
                      // Insert new subscriber record
                      $this->db->insert(KN_SUBSCRIBERLIST, [
                          'subscriber_id' => $vr->subscriber_id,
                          'proforma_id' => $vr->id,
                          'subscriber_refno' => $Varsubscriber_refno,
                          'subscriber_startdate' => $Varstart_date,
                          'subscriber_enddate' => $Varend_date,
                          'renewal_daysleft' => $daysleftrenewal,
                          'pckg_saved_status'=>1,
                          'dept_saved_status'=>1,
                          'datecreated' => date('Y-m-d H:i:s'),
                          'dateupdated' => date('Y-m-d H:i:s')
                      ]);
  
                      // Get inserted ID and update other tables if successful
                      if ($primaryId = $this->db->insert_id()) {
                          $updateData = ['status' => 1, 'dateupdated' => date('Y-m-d H:i:s')];
  
                          $this->db->where('subscriber_id', $vr->subscriber_id)
                                   ->where('proforma_id', $vr->id)
                                   ->update(KN_MUSERS, $updateData);
  
                          $this->db->where('id', $vr->id)
                                   ->update(KN_PROFORMAINVOICE, $updateData);
                      }
                  }
              }
          }
      }
  }
  

}