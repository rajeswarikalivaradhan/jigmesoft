<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob extends CI_Controller {

	public $companyid;

	public function __construct() {
		parent::__construct();
		error_reporting(E_ALL);
ini_set('display_errors', 1);
		$this->load->library('input');
		$this->load->helper('xssclean');
		//$ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
		//$this->companyid = $ArrUserLoggedInfo['companyid'];
	}

	public function test_cache() {
	    echo '<pre>'; print_r(opcache_get_status());
    }

	public function index() {

		$this->load->model('cronmodel');


		$VarToday = date('d', strtotime('-1 day'));


		$VarMo = date('m');

		$VarYe = date('Y');

		$VarDateToCheck = date($VarYe . '-' . $VarMo . '-' . $VarToday . ' 00:00:00');

		$VarUpdatedBy = fnGetUserLoggedInfo();


		//echo $this->cronmodel->chgExpOrders($VarDateToCheck,2);


		$ArrUserInfo = fnGetUserLoggedInfo(1);


		//print_r($this->cronmodel->chgExpOrders($VarDateToCheck,2,$ArrUserInfo['id']));

		//die();


		if ($this->cronmodel->chgExpOrders($VarDateToCheck, 2, $VarUpdatedBy))
		{

			echo 'invoice status change to deleted status';

		} else
		{

			echo 'err';

		}


	}

	public function test() {
        $this->load->view(CNFCOMPANY.'collapsed-sidebar');
    }
	public function gethtmlforpdf() {
		$this->load->model('commonmodel');
		$VarId = $this->input->post('id');
		$ArrObjInvoice = $this->commonmodel->getBomPurchaseIndentInvoice($VarId, $this->companyid);
		if (empty($ArrObjInvoice[0]))
		{
			$ArrData['ArrBasicInfo'] = array();
		} else
		{
			$ArrData['ArrBasicInfo'] = $ArrObjInvoice[0];
		}

	}

/*	public function developerTest() {
        $this->load->view('developerTest');
	}

    public function developerPage2() {
        $this->load->view('developerPage2');
    }

	public function saveJxl() {
        $ArrData = json_decode(xssclean($this->input->post('d')),true);
        foreach ($ArrData as $data) {
            $this->db->insert('testTable',$data);
        }

    }

    public function get1TblData() {
        $data = $this->db->from('testTable')->get()->result_array();
        if(!empty($data))
            echo json_encode($data);
        else {
            echo json_encode(array(array()));
        }
    }*/

	public function googleSearch() {
		$this->load->view('searchCurrency');
	}
	public function runCurrency() {
		/* API URL */

		//$url = 'http://data.fixer.io/api/latest?access_key=f7850401763f9838307c8ee804df0502&symbols=INR,SGD,HKD,MYR,USD,JPY,GBP,AUD,CAD,CHF,CNH,SEK,NZD';
		//$url = 'http://xecdapi.xe.com/v1/convert_from.json/?from=USD&to=INR&amount=1';
		//$url = 'http://api.currencylayer.com/live?access_key=4806602781a8e33d657e0bac2b6f87b8&currencies=AUD,EUR,GBP,INR,SGD,HKD,MYR,JPY,CAD,CHF,CNH,SEK,NZD';
		$url = 'https://www.googleapis.com/customsearch/v1?key=AIzaSyA65MkFDAHuG0WMoOAmx0xjL5FIruVD4aA&cx=002056607121710290324:c3fdk6o-nni&q=1+usd+to+inr';
		//$url = 'https://www.google.com/?q=1+usd+to+inr';
		//$url = 'https://cse.google.com/cse?cx=002056607121710290324:c3fdk6o-nni';
        //$url = 'https://api.coinbase.com/v2/exchange-rates?currency=BTC';
		/* Init cURL resource */

		$ch = curl_init($url);
//echo '<pre>'; print_r($ch); die('die');

		/* Array Parameter Data */

		//$data = ['name' => 'Hardik', 'email' => 'itsolutionstuff@gmail.com'];


		/* pass encoded JSON string to the POST fields */

		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


		/* set the content type json */

		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));


		/* set return type json */

		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);


		/* execute request */

		$jsonResult = curl_exec($ch);
		echo '<pre>'; print_r($jsonResult); die('die');
		//$ArrRes = json_decode($jsonResult,true);
		//$ArrCurrency = [];
		//foreach ($ArrRes as $res) {
			//$ArrCurrency[] = $res['quotes'];
		//}
		//echo '<pre>'; print_r($ArrRes); die('die');
		//echo '<pre>'; print_r($result); die('die');

		/* close cURL resource */

		//curl_close($ch);

		//$VarSearch = 'https://www.google.com/search?q=1+usd+to+inr';



		$Res = file_get_contents($correctUrl);
		echo '<pre>'; print_r($Res); die('die');
		$this->load->model('commonmodel');


		$this->commonmodel->updateCurrency($ArrCurrency,date('Y-m-d H:i:s'));
	}
	public function updaterenewal() {
		$this->load->model('cronmodel');
		$result = $this->cronmodel->updatedays();
	
		if (!empty($result)) {
			$subscriberUpdates = [];
			$userUpdates = [];
			$invoiceUpdates = [];
			$currentDate = date('Y-m-d H:i:s');
	
			foreach ($result as $subscriber) {
				// Determine the new status
				$status = ($subscriber->no_of_days_left == 0) ? 2 : 1;
	
				// Prepare data for KN_SUBSCRIBERLIST update
				$subscriberUpdates[] = [
					'id' => $subscriber->id,
					'renewal_daysleft' => $subscriber->no_of_days_left,
					'status' => $status,
					'dateupdated' => $currentDate
				];
	
				// If status is 2, prepare updates for KNUSERS and KN_PROFORMAINVOICE
				if ($status == 2) {
					// Update KNUSERS
					$userUpdates[] = [
						'subscriber_id' => $subscriber->subscriber_id,
						'proforma_id' => $subscriber->proforma_id, // Ensure proforma_id exists in $result
						'status' => 2,
						'dateupdated' => $currentDate
					];
	
					// Update KN_PROFORMAINVOICE
					$invoiceUpdates[] = [
						'proforma_id' => $subscriber->proforma_id,
						'status' => 2,
						'dateupdated' => $currentDate
					];
				}
			}
	
			// Batch update KN_SUBSCRIBERLIST
			if (!empty($subscriberUpdates)) {
				$this->db->update_batch(KN_SUBSCRIBERLIST, $subscriberUpdates, 'id');
			}
	
			// Update KNUSERS for each matching record
			if (!empty($userUpdates)) {
				foreach ($userUpdates as $userUpdate) {
					$this->db->where('subscriber_id', $userUpdate['subscriber_id'])
							 ->where('proforma_id', $userUpdate['proforma_id'])
							 ->update(KN_MUSERS, [
								 'status' => 2,
								 'dateupdated' => $userUpdate['dateupdated']
							 ]);
				}
			}
	
			// Update KN_PROFORMAINVOICE for each matching record
			if (!empty($invoiceUpdates)) {
				foreach ($invoiceUpdates as $invoiceUpdate) {
					$this->db->where('id', $invoiceUpdate['proforma_id'])
							 ->update(KN_PROFORMAINVOICE, [
								 'status' => 2,
								 'dateupdated' => $invoiceUpdate['dateupdated']
							 ]);
				}
			}
		} else {
			log_message('info', 'No subscribers found for renewal update.');
		}
	}


}