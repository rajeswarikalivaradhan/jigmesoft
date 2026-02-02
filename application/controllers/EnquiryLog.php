<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class enquiryLog extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
    }

    public function index() {
	}

	public function enquiryLogList() {
        $VarFrom                = xssclean($this->input->post('rFrom'));
        $VarEnquiryId           = xssclean($this->input->post('enquiryId'));
        $ArrFnlList = [];
        if($VarFrom == 1) {
            $this->db->select('oe.id,orderenqrefno,br.brandname,DATE_FORMAT(oe.datecreated,"%d-%m-%Y %H:%i:%s") as formattedDateCreated,
            merchantnote,comments,order_status_value,DATE_FORMAT(oe.dateupdated,"%d-%m-%Y %H:%i:%s") as formattedDateUpdated');
            $this->db->from(KN_ORDER_ENQUIRY_LOG. ' AS oe');
            $this->db->where('oe.enquiryId',$VarEnquiryId);
            $this->db->join(KN_MASTER_BRANDS.' AS br','br.id = oe.brandId');
            $Qry = $this->db->get();
            $ObjResult = $Qry->result();
            $i = 0;
            if(!empty($ObjResult)) {
                foreach ($ObjResult as $key => $Obj) {
                    $ArrFnlList[$i]['id']						    = $Obj->id;
                    $ArrFnlList[$i]['orderEnqRefNo']					= $Obj->orderenqrefno;
                    $ArrFnlList[$i]['reqDateTime']					= $Obj->formattedDateCreated;
                    $ArrFnlList[$i]['brn']					= $Obj->brandname;
                    $ArrFnlList[$i]['merchantRemarks']					= $Obj->merchantnote;
                    $ArrFnlList[$i]['manRemarks']					= $Obj->comments;
                    $ArrFnlList[$i]['order_status_value']					= $Obj->order_status_value;
                    $ArrFnlList[$i]['dateupdated']					= $Obj->formattedDateUpdated;
                    $i++;
                }
                echo json_encode(array('errCode'=>1,'re'=>$ArrFnlList,'cn'=>$i));
            }
            else {
                echo json_encode(array('errCode'=>-1,'re'=>$ArrFnlList,'cn'=>$i));
            }
            unset($ArrFnlList); die();
        }
    }

    public function enquiryLogDetail() {
        $this->load->model('commonmodel');
        /*
         * For getting folder name of uploads in for order enquiry we need to send user type
         * */
        $ArrUserType = unserialize(ARRUSERTYPE);
        $VarHashedId = $this->uri->segment(3);
        $ArrEnquiryInfo = array();
        if ($VarHashedId <> '' && is_numeric(base64_decode(urldecode($VarHashedId)))) {
            $VarId = base64_decode(urldecode($VarHashedId));
            $ResObj = $this->fnGetInfo($VarId);			
            $ArrEnquiryInfo = $ResObj[0];
        }
        $ArrUserInfo = fnGetUserLoggedInfo(1);
        $VarUserType = $ArrUserType[$ArrUserInfo['usertype']];
        $ArrOrderStatus = unserialize(ORDERENQUIRYSTATUS);
        $ArrCountries = unserialize(ARRCOUNTRYLIST);
        $ArrEnquiryType = ARRENQUIRYTYPE;
        $ArrModeType = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_MODEOFENQUIRY, 'id,modeofenquiry as name', array('status' => '1', 'companyid' => $ArrUserInfo['companyid']));
        $ArrCurrency = unserialize(ARRCURRENCYLIST);
        $ArrBrand = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BRANDS . ' AS br', 'br.id,br.brandname', array('br.status' => '1', 'br.companyid' => $ArrUserInfo['companyid']),3);
        $ArrBuyer = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_BUYER . ' AS byr', 'byr.id,byr.buyername', array('byr.status' =>'1', 'byr.companyid' => $ArrUserInfo['companyid']),3);
        $this->load->view("enquiryLogDetail", array('ArrEnquiryType' => $ArrEnquiryType, 'ArrCountries' => $ArrCountries,
            'ArrModeType' => $ArrModeType, 'ArrCurrency' => $ArrCurrency,'ArrBrand' => $ArrBrand,'ArrBuyer'=>$ArrBuyer,
			'ArrEnquiryInfo' => $ArrEnquiryInfo, 'ArrOrderStatus' => $ArrOrderStatus,'UserType'=>$VarUserType
        ));
    }

    public function fnGetInfo($VarId) {       
        $VarSqlEnq                  = "SELECT e.id,e.enquiryId,e.enquirytype,orderenqrefno,pricequotedfor,e.modeofenquiry as modeofenquiryid,
		e.countryid,e.currency,e.stylenamerefno,e.styledesc,e.pcsorset,e.quotedprice,e.buyerprice,brandId,buyerId,
e.confirmprice,e.exporderqty,e.reqforisrior,e.merchantid,DATE_FORMAT(e.datecreated,\"%d-%m-%Y %H:%i:%s\") as formattedDateCreated,
DATE_FORMAT(e.dateupdated,\"%d-%m-%Y %H:%i:%s\") as formattedDateUpdated,e.enquirydate,e.order_status_value,e.comments,e.merchantnote,mo.modeofenquiry 
FROM ".KN_ORDER_ENQUIRY_LOG." AS e INNER JOIN ".KN_MASTER_MODEOFENQUIRY." as mo ON mo.id = e.modeofenquiry WHERE e.id = '$VarId' ";
        $ResSqlEnq					= $this->db->query($VarSqlEnq)->result();      
		return $ResSqlEnq;
    }

  
}