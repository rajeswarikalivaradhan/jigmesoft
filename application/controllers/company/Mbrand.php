<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class mbrand extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(CNFCOMPANY . "mbrandmodel");
        $this->load->model(CNFCOMPANY . "mbuyermodel");
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
        $AllBuyers = $this->mbuyermodel->fnGetAllBuyer($VarStatus = 1, $VarCompanyId);
        $ArrData['ArrBuyers'] = $AllBuyers;
        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->mbrandmodel->fnGetInfo('', '', $VarId);
            $ArrData['brands'] = $ArrResults;
            $ArrData['VarId'] = $ArrResults[0]->id;
        } else {

        }
        $this->load->view(CNFCOMPANY . 'addeditbrand', $ArrData);
    }

    public function updateBrandInfo()
    {
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }
        $ArrResult = array();
        $VarBrand = xssclean($this->input->post('brn'));
        $VarBuyerId = xssclean($this->input->post('byrId'));
        $VarStatus = xssclean($this->input->post('s'));
        $VarId = xssclean($this->input->post('id'));
        $VarUpdatedBy = fnGetUserLoggedInfo();
        if ($VarBrand <> '') {
            $ArrData = array('id' => $VarId, 'brandname' => $VarBrand, 'ref_buyerid' => $VarBuyerId, 'companyid' => $VarCompanyId,
                'status' => $VarStatus, 'updatedby' => $VarUpdatedBy, 'dateupdated' => date('Y-m-d H:i:s'));
            if (empty($VarId)) {
                $ArrData['datecreated'] = date('Y-m-d H:i:s');
            }
            $ArrResult = $this->mbrandmodel->saveInfo($ArrData);
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input!';
        }
        echo json_encode($ArrResult);
    }

    public function manage()
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

            $config['total_rows'] = $this->mbrandmodel->fnCount($VarBrand, $VarStatus, $VarAfilter);
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


            $ArrList = $this->mbrandmodel->fnList($VarBrand, $VarStatus, $this->limit, $offset, $sortby, $sortorder, $VarAfilter)->result();

            $data['pagination'] = $this->pagination->create_linkswithajax('Brand');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);

            foreach ($ArrList as $ObjUnit) {
                $ArrFnlList[$i]['id'] = $ObjUnit->id;

                $ArrFnlList[$i]['brand'] = $ObjUnit->brandname;
                $ArrFnlList[$i]['du'] = dateTimeHelp($ObjUnit->dateupdated . false);
                $ArrFnlList[$i]['ub'] = $ObjUnit->contactname;
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

            $this->mbrandmodel->fnChangeComStat($VarCid, $VarActDeactOption);

        }

        echo json_encode(array('errcode' => 1));

    }


}