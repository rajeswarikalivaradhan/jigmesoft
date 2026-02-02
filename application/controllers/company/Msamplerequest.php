<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msamplerequest extends CI_Controller {
    private $limit = 5;
    private $companyid = 0;
    private $userid = 0;
    public function __construct() {
        parent::__construct();
        $this->load->model(CNFCOMPANY."msamplerequestmodel");
        $this->load->helper('xssclean');
        $this->load->helper('common');
        $this->load->model(CNFCOMPANY.'menquirymodel');
        $this->load->model('commonmodel');
        error_reporting(E_ALL);
        fnIfCheckUserLoggedIn();
        $VarUserInfo                                            = fnGetUserLoggedInfo(1);
        if(isset($VarUserInfo['companyid']) && $VarUserInfo['companyid'] >= 1) {
            $this->companyid                                       = $VarUserInfo['companyid'];
            $this->userid                                       = $VarUserInfo['id'];
        }
    }
    function merchantsamplesentlist() {
        $ArrData = array();
        $this->load->view(CNFCOMPANY.'merchantsamplesentlist',$ArrData);
    }
    function addeditmerchantsample() {
        $this->load->model(CNFCADMIN.'companymodel');
        $this->load->model(CNFCOMPANY.'orderentrymodel');
        $this->load->model(CNFCOMPANY.'mcadrequestmodel');
        $VarOrderId = base64_decode(urldecode($this->uri->segment(4)));
        $ArrOrderEnq = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarOrderId,$this->companyid);
        //echo '<pre>'; print_r($ArrOrderEnq); die('');
        $VarCountry = $this->commonmodel->fnGetAllTableInfo(KN_COUNTRIES,'id,countryname',array('id'=>'103'));
        $ArrRequirement = $this->commonmodel->fnGetAllTableInfo(KN_CAD_REQUIREMENTPURPOSE,'id,requirement',array('type'=>'1','status'=>'1'));
        $ArrPurpose = $this->commonmodel->fnGetAllTableInfo(KN_CAD_REQUIREMENTPURPOSE,'id,purpose',array('type'=>'2','status'=>'1'));
        $VarOrderEntryCommon = $this->mcadrequestmodel->getOrderEntryDataFromFifthTbl($VarOrderId);
        $ArrOrderEntry = $ArrOrderEnq[0];
//echo '<pre>'; print_r($ArrOrderEntry);

        if($VarOrderId >= 1) {
            $ArrOrderDatas = $this->orderentrymodel->getOrderDataByWip('',$VarOrderId,$this->companyid);
            $ArrSizeChart = $this->orderentrymodel->getSizeChart($VarOrderId,$this->companyid);
        }
        //echo '<pre>'; print_r($ArrOrderDatas); die('');
        $ArrCompanyRes                                  = $this->companymodel->fnGetCompanyInfo($this->companyid);
        $ArrCompanyInfo                                 = array('companyname' => $ArrCompanyRes[0]['companyname'], 'address' => $ArrCompanyRes[0]['address']);
        $ArrMerchantDetails = $this->orderentrymodel->getMerchantDetails($this->companyid,$ArrOrderEntry['merchantid'],1);

        $ArrTeamDetails = $this->commonmodel->getTeamDetails('',$ArrOrderDatas[0]->teamid,1);
        //echo '<pre>'; print_r($ArrTeamDetails); die('');
        $ArrBb = $this->orderentrymodel->fnBuyerbyBrandId($ArrOrderEntry['brandbuyerid']);
        $this->load->view(CNFCOMPANY.'addeditsamplerequest',array('ArrCompanyInfo'=>$ArrCompanyInfo,'ArrMerchantDetails'=>@$ArrMerchantDetails[0],'ArrTeamDetails'=>@$ArrTeamDetails[0],
            'ArrOrderEntry'=>$ArrOrderEntry,'VarOrderEntryCommon'=>$VarOrderEntryCommon,'desticountry'=>$VarCountry,'ArrRequirement'=>$ArrRequirement,'ArrPurpose'=>$ArrPurpose,
            'VarBrand'=>$ArrBb[0]['brandname'],'VarBuyer'=>$ArrBb[0]['buyername'],'ArrReqSize'=>explode(',',$ArrSizeChart[0]->sizechartvalue),'ArrOrderDatas'=>@$ArrOrderDatas[0]));
    }

    function fnUploadAttachment() {
        $VarFdrName = $_POST['samplerequest'];
        $VarDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/samplerequest/".$VarFdrName."/";
        if(!file_exists($VarDir)) {
            mkdir($VarDir,0777,true);
        }
        if (isset($_FILES["myfile"])) {
            $ret = array();
            $error = $_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData()
            if (!is_array($_FILES["myfile"]["name"])) { //single file
                $fileName = $_FILES["myfile"]["name"];
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $VarDir . $fileName);
                $ret[] = $fileName;
            } else { //Multiple files, file[]
                $fileCount = count($_FILES["myfile"]["name"]);
                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName = $_FILES["myfile"]["name"][$i];
                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $VarDir . $fileName);
                    $ret[] = $fileName;
                }
            }
            //echo '<pre>'; print_r($ret); die('');
            echo json_encode($ret);
        }

    }

}