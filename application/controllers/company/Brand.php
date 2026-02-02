<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class brand extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->model(CNFCOMPANY . "mbrandmodel");
        $this->load->model(CNFCOMPANY . "Brandmodel");
        //$this->load->model(CNFCOMPANY . "mbuyermodel");
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $this->limit = LIMITPERPAGE;
    }

    public function addedit()
    {
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);

        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        // $AllBuyers = $this->mbuyermodel->fnGetAllBuyer($VarStatus = 1, $VarCompanyId);
        // $ArrData['ArrBuyers'] = $AllBuyers;
        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->Brandmodel->fnGetInfo('', '', $VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {

        }
        $this->load->view(CNFCOMPANY . 'addeditbrandnew', $ArrData);
    }

    public function updateBrandInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarBrand = xssclean($this->input->post('brn'));
        $VarBuyer= xssclean($this->input->post('byr'));
        $VarAddr = xssclean($this->input->post('addr'));
        $VarCity = xssclean($this->input->post('cty'));
        $VarState= xssclean($this->input->post('st'));
        $VarCountry = xssclean($this->input->post('cy'));
        $VarPincode = xssclean($this->input->post('pin'));
        $VarEmailId = xssclean($this->input->post('em'));
        $VarMobno = xssclean($this->input->post('mbno'));
        $VarPhno = xssclean($this->input->post('phno'));
        $VarContacperson= xssclean($this->input->post('cp'));
        $VarBusinesstype= xssclean($this->input->post('bt'));
        $VarBusiness_fashiontype= xssclean($this->input->post('bft'));
        $VarGSTno = xssclean($this->input->post('gst'));
        $VarIECode = xssclean($this->input->post('iec'));
        $VarBnkname = xssclean($this->input->post('bnk'));
        $VarAcntname = xssclean($this->input->post('actn'));
        $VarAcntno = xssclean($this->input->post('actno'));
        $VarIFSC = xssclean($this->input->post('ifsc'));
        $VarSwift = xssclean($this->input->post('swift'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        $VarUpdatedBy = fnGetUserLoggedInfo();
        
        if ($VarBrand <> '') {
            $ArrData = array('id' => $VarId, 
                             'brandname' => $VarBrand, 
                             'buyername' => $VarBuyer,
                             'address'=>$VarAddr,
                             'emailid'=>$VarEmailId,
                             'phone'=>$VarPhno,
                             'mobile'=>$VarMobno,
                             'city'=>$VarCity,
                             'state'=>$VarState,
                             'country'=>$VarCountry,
                             'pincode'=>$VarPincode,
                             'contactperson'=>$VarContacperson,
                             'brand_businesstype'=>$VarBusinesstype,
                             'brand_fashiontype'=>$VarBusiness_fashiontype,
                             'gstno'=>$VarGSTno,
                             'iecode'=>$VarIECode,
                             'bankname'=>$VarBnkname,
                             'accountname'=>$VarAcntname,
                             'accountno'=>$VarAcntno,
                             'ifsccode'=>$VarIFSC,
                             'swiftcode'=>$VarSwift,
                             'companyid' => $VarCompanyId,
                             'status' => $VarStatus, 
                             'updatedby' => $VarUpdatedBy, 
                             'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->Brandmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }

    public function manages()
    {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarBrand = xssclean($this->input->post('br'));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        
        $ArrDbCols = array('brandname', 'contactname', 'status', 'dateupdated');
        if ($VarFrom == 1) {

            $VarURLSegment = 4;

            $this->load->library('pagination');

            $config['base_url'] = base_url() . CNFCOMPANY . 'mbrand/manage/';

            $config['total_rows'] = $this->Brandmodel->fnCount($VarBrand, $VarStatus, $VarAfilter);
            $config['per_page'] = $this->limit;

            $config['uri_segment'] = $VarURLSegment;

            $offset = $this->uri->segment($VarURLSegment);

            $this->pagination->initialize($config);

            $sortby = "dateupdated";

            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {

                if (array_key_exists($clickedColumnId, $ArrDbCols)) {

                    $sortby = $ArrDbCols[$clickedColumnId];

                }

                $sortorder = $newsortorder;

            }


            $ArrList = $this->Brandmodel->fnList($VarBrand, $VarStatus, $this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();

            $data['pagination'] = $this->pagination->create_linkswithajax('Brand');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);

            foreach ($ArrList as $ObjUnit) {
                $ArrFnlList[$i]['id'] = $ObjUnit->id;

                $ArrFnlList[$i]['brand'] = $ObjUnit->brandname;
                $ArrFnlList[$i]['du'] = dateTimeHelp($ObjUnit->dateupdated . false);
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->contactname)?$ObjUnit->contactname:"";
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];

                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {

            $this->load->view(CNFCOMPANY . 'managebrand');

        }

    }

    function changemStatus()
    {
        $VarActDeactOption = xssclean($this->input->post('actdeactFabType'));

        $VarCid = xssclean($this->input->post('cid'));

        if ($VarActDeactOption <> '' && $VarCid <> '') {

            $this->Brandmodel->fnChangeComStat($VarCid, $VarActDeactOption);

        }

        echo json_encode(array('errcode' => 1));

    }
    
    //////////////newly added////////////////
    public function manage()
    {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarBrand =trim(xssclean($this->input->post('bn')));
        $VarBuyer = trim(xssclean($this->input->post('byn')));
        $VarContactPerson =trim(xssclean($this->input->post('cp')));
        $VarEmail_id = trim(xssclean($this->input->post('em')));
        $VarMobno = xssclean(preg_replace('/^\s+/', '+ ', trim($this->input->post('mno'))));
        $VarBusiness_fashiontype= trim(xssclean($this->input->post('bft')));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        
        $ArrDbCols = array('brandname', 'contactname', 'status', 'dateupdated');
        if ($VarFrom == 1) {

            $VarURLSegment = 4;

            $this->load->library('pagination');

            $config['base_url'] = base_url() . CNFCOMPANY . 'brand/manage/';

            $config['total_rows'] = $this->Brandmodel->fnCount($VarBrand,$VarBuyer,$VarContactPerson,$VarEmail_id,$VarMobno,$VarBusiness_fashiontype,$VarStatus,$VarAfilter);
            $config['per_page'] = $this->limit;

            $config['uri_segment'] = $VarURLSegment;

            $offset = $this->uri->segment($VarURLSegment);

            $this->pagination->initialize($config);

            $sortby = "dateupdated";

            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {

                if (array_key_exists($clickedColumnId, $ArrDbCols)) {

                    $sortby = $ArrDbCols[$clickedColumnId];

                }

                $sortorder = $newsortorder;

            }


            $ArrList = $this->Brandmodel->fnList($VarBrand,$VarBuyer,$VarContactPerson,$VarEmail_id,$VarMobno,$VarBusiness_fashiontype,$VarStatus,$this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();

            $data['pagination'] = $this->pagination->create_linkswithajax('Brand');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);

            foreach ($ArrList as $ObjUnit) {
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['brand'] = $ObjUnit->brandname;
                $ArrFnlList[$i]['buyer'] = $ObjUnit->buyername;
                $ArrFnlList[$i]['cp'] =!empty($ObjUnit->contactperson)?$ObjUnit->contactperson:'';
                $ArrFnlList[$i]['em'] =!empty($ObjUnit->emailid)?$ObjUnit->emailid:'';
                $ArrFnlList[$i]['mbno']=!empty($ObjUnit->mobile)?$ObjUnit->mobile:'';
                $ArrFnlList[$i]['bft'] =!empty($ObjUnit->brand_fashiontype)?$ObjUnit->brand_fashiontype:'';
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->contactname)?$ObjUnit->contactname:"";
                $ArrFnlList[$i]['du'] = date('d-m-Y H:i A', strtotime($ObjUnit->dateupdated));
                
                
                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {

            $this->load->view(CNFCOMPANY . 'managebrandbuyer');

        }

    }
}