<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class BudgetCosting extends CI_Controller
{

    public $companyid;
    public $userid;
    public $mysqldatetime;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $this->load->model(CNFCOMPANY . 'menquirymodel');
        $this->load->model(CNFCOMPANY . "orderentrymodel");
        $this->load->model("commonmodel");
        $this->load->model(CNFCADMIN . 'companymodel');
        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->userid        = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
    }

    public function index()
    {
        $VarId          = $this->uri->segment(3);
        $VarEnqId       = '';
        $ArrEnquiryInfo = array();
        if ($VarId <> '' && is_numeric(base64_decode(urldecode($VarId))))
        {
            $VarEnqId       = base64_decode(urldecode($VarId));
            $ArrEnquiryInfo = $this->menquirymodel->fnGetInfo('', $VarEnqId, $this->companyid);
        }

        $ArrCommonHeaderData = $this->commonheaderdata($VarEnqId);

        $userInfoQry = 'SELECT c.id AS component_id, c.comp_name AS component_name, c.dying_type FROM tbl_components c WHERE c.enquiry_id=' . $VarEnqId;
        $components  = $this->db->query($userInfoQry)->result_array();

        $this->load->view('budgetCosting/index', array(
            'components'           => $components,
            'VarEnqId'             => $VarEnqId,
            'ArrEnquiryInfo'       => $ArrEnquiryInfo,
            'ArrCommonHeaderData' => $ArrCommonHeaderData
        ));
    }

    public function commonheaderdata($VarEnquiryId)
    {
        $ArrCompanyRes       = $this->companymodel->fnGetCompanyInfo($this->companyid);
        $ArrEnquiryDetails   = $this->orderentrymodel->fnGetOrderEnquiryInfo($VarEnquiryId, $this->companyid);
        $ArrEnquiryDetails   = @$ArrEnquiryDetails[0];
        $VarHashEnquiryId    = $this->uri->segment(3);
        $ArrMerchant         = $this->commonmodel->getMerchantData($this->companyid, 1, $ArrEnquiryDetails['merchantid']);
        $ArrTeam             = $this->commonmodel->getTeamDetails($this->companyid, $ArrEnquiryDetails['merchantid']);
        $ArrCommonData       = $this->orderentrymodel->getCommonData($VarEnquiryId, $this->companyid);
        $ArrCommonHeaderData = array(
            'companyName'       => @$ArrCompanyRes[0]['companyname'], 'companyAddress'    => @$ArrCompanyRes[0]['address'],
            'VarEnquiryId'      => $VarEnquiryId, 'VarHashEnquiryId'  => @$VarHashEnquiryId, 'merchantName'      => @$ArrMerchant[0]['contactname'],
            'merchantMobile'    => @$ArrMerchant[0]['mobile'], 'merchantCode'      => @$ArrMerchant[0]['code'],
            'merchantEmail'     => @$ArrMerchant[0]['username'], 'ArrEnquiryDetails' => $ArrEnquiryDetails,
            'ArrCommonData'     => @$ArrCommonData, 'ArrTeam'           => @$ArrTeam[0]
        );

        return $ArrCommonHeaderData;
    }

}
