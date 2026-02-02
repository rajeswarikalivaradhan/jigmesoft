<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class fabricprogram extends CI_Controller {
    private $limit = 2;

    public function __construct() {
        parent::__construct();
        $this->load->model(CNFCOMPANY."fabricprogrammodel");
        $this->load->helper('xssclean');
        $this->load->helper('common');
        $this->load->model("commonmodel");

        fnIfCheckUserLoggedIn();
        $VarUserInfo                                            = fnGetUserLoggedInfo(1);
        if(isset($VarUserInfo['companyid'])) {
            $this->VarCompanyId                                 = $VarUserInfo['companyid'];
        }
    }

    public function index() {
        $ArrYarnPurchaseType = array();
        $VarFrom										= xssclean($this->input->post('rfrom'));

        if($VarFrom==1) {
            //echo '<pre>'; print_r($VarFrom); die('');
            /*$YarnCount                                      = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_YARN,'count',array('type'=>'1','status'=>"1",'companyid'=>$this->VarCompanyId),3);
            foreach($YarnCount as $value) {
                $ArrYarnCount[] = $value['count'];
            }

            echo json_encode(array('errcode'=>1,'ArrYarnCount'=>$ArrYarnCount));*/
        }
        else {
            $Arrfabricfinish                                = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FABRIC_FINISH,'id,name',array('status'=>"1",'companyid'=>$this->VarCompanyId),3);
            $fabricfinishStageForm                          = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_FF_STAGE_FORM,'id,dpfname',array('status'=>"1",'companyid'=>$this->VarCompanyId),3);
            $YarnCount                                      = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_YARN,'count',array('type'=>'1','status'=>"1",'companyid'=>$this->VarCompanyId),3);
            $YarnPurchaseType                               = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_YARN,'yarnpurchasetype',array('type'=>'3','status'=>"1",'companyid'=>$this->VarCompanyId),3);
            foreach($YarnPurchaseType as $value) {
                $ArrYarnPurchaseType[] = $value['yarnpurchasetype'];
            }
            foreach($YarnCount as $value) {
                $ArrYarnCount[] = $value['count'];
            }

            $this->load->view(CNFCOMPANY.'mfabricindex',array('fabricfinish'=>json_encode($Arrfabricfinish),'fabricfinishStageForm'=>json_encode($fabricfinishStageForm),
                'ArrYarnCount'=>json_encode($ArrYarnCount),'ArrYarnPurchaseType'=>json_encode($ArrYarnPurchaseType)));
        }
/*        $Arrfabricfinish                                = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_WET_PROCESSING_GREIGE,'id,wpgname as name',array('status'=>"1",'companyid'=>$this->VarCompanyId),3);
        $ArrfabricfinishStageForm                       = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_DRY_PROCESSING_FINISHING,'id,dpfname as name',array('status'=>"1",'companyid'=>$this->VarCompanyId),3);
        //$ArrDyeingType                                       = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_DYEING_TYPE,'dyeingname as name',array('status'=>"1",'companyid'=>$VarCompanyId),3);

        $YarnCount                                      = $this->commonmodel->fnGetAllTableInfo(KN_MASTER_YARN,'count',array('type'=>'1','status'=>"1",'companyid'=>$this->VarCompanyId),3);
        foreach($YarnCount as $value) {
            $ArrYarnCount[] = $value['count'];
        }
        //echo '<pre>'; print_r($ArrYarnCount); die('');
        $this->load->view(CNFCOMPANY.'mfabricindex',array('fabricfinish'=>json_encode($Arrfabricfinish),'fabricfinishStageForm'=>json_encode($ArrfabricfinishStageForm),
            'ArrYarnCount'=>$ArrYarnCount));*/
    }

    function mfabricindexdropdowndata() {
        echo $this->fabricprogrammodel->fnTest();

        //echo $this->VarCompanyId;

    }

}