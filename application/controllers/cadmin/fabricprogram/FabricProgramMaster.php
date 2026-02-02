<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class fabricProgramMaster extends CI_Controller {
    private $limit = 2;

    public function __construct() {
        parent::__construct();
        $this->load->model(CNFCADMIN."fabricProgram/knittingProgram");
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

    }

    public function knittingMachineMake() {
        echo '<pre>'; print_r(array()); die('die');
        $ArrProfileInfo = fnGetUserLoggedInfo(1);
        if(xssclean($this->input->post('rFrom')) == 1) {
            $this->load->model('queuesmodel');
            $ArrList = $this->fabricProgramKnitProgram->knittingMachineMake();
            $data = array();
            $ArrStatus = unserialize(ARRSTATUS);
            $ArrORDERENQUIRYSTATUS = unserialize(ORDERENQUIRYSTATUS);
            $ArrRequestType = unserialize(ARRREQUESTTYPE);
            $ArrBomRequirement = unserialize(ARRBOMREQUIREMENT);
            $QueueListDetailPageUrl = '';
            foreach ($ArrList as $Obj) {
                $row = array();
                $row[] = '<input type="checkbox" class="allcbox" id="' . $Obj->id . '">';
                if ($Obj->requestlisttypeid == 1) {
                    $QueueListDetailPageUrl = base_url('caduser/cadqueuelistdetail') . '/' . urlencode(base64_encode($Obj->id));
                    $row[] = '<a href="' . $QueueListDetailPageUrl . '">' . $Obj->isriorcode . '</a>';
                    $row[] = $Obj->brandname . ' / ' . $Obj->buyername;
                    $row[] = $Obj->queueno;
                    $row[] = 'CAD';
                    if (empty($Obj->jsondatagrid)) {
                        $row[] = '-';
                    } else {
                        $jsonGrid = json_decode($Obj->jsondatagrid, true);
                        $row[] = $jsonGrid[0][5];
                    }
                } elseif ($Obj->requestlisttypeid == 2) {
                    $QueueListDetailPageUrl = base_url('samplinguser/queuelistdetail') . '/' . urlencode(base64_encode($Obj->id));
                    $row[] = '<a href="' . $QueueListDetailPageUrl . '">' . $Obj->isriorcode . '</a>';
                    $row[] = $Obj->brandname . ' / ' . $Obj->buyername;
                    $row[] = $Obj->queueno;
                    $row[] = 'SAMPLE';
                    if (empty($Obj->jsondatagrid)) {
                        $row[] = '-';
                    } else {
                        $jsonGrid = json_decode($Obj->jsondatagrid, true);
                        $row[] = $jsonGrid[0][5];
                    }
                } elseif ($Obj->requestlisttypeid == 3) {
                    $QueueListDetailPageUrl = base_url('purchaseuser/preprarebompind') . '/' . urlencode(base64_encode($Obj->id));
                    $row[] = '<a href="' . $QueueListDetailPageUrl . '">' . $Obj->isriorcode . '</a>';
                    $row[] = $Obj->brandname . ' / ' . $Obj->buyername;
                    $row[] = $Obj->queueno;
                    $row[] = 'BOM';
                    $row[] = $ArrBomRequirement[$Obj->requirementforbom];
                } else {
                }
                $row[] = $Obj->datecreated;
                $row[] = $Obj->cutoffdatetime;
                $row[] = $ArrRequestType[$Obj->approvaltype];
                $row[] = $Obj->mgmt;
                $row[] = $Obj->merchant;
                if ($Obj->deptcurrentstatus == 0) {
                    $VarCs = '-';
                } elseif ($Obj->queuecompletestatus == 1) {
                    $VarCs = 'JOB DONE';
                } elseif ($Obj->queuecompletestatus == 2) {
                    $VarCs = 'RE SCHEDULED';
                } else {
                    $VarCs = $ArrORDERENQUIRYSTATUS[$Obj->deptcurrentstatus];
                }
                $row[] = $VarCs;
                $row[] = $Obj->datecreated;
                $row[] = $ArrStatus[$Obj->status];
                $data[] = $row;
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->queuesmodel->count_all(),
                "recordsFiltered" => $this->queuesmodel->count_filtered(),
                "data" => $data,
            );
            echo json_encode($output);
        }
        else {
            $this->load->view('allqueuelist', array('usertype' => $ArrProfileInfo['usertype']));
        }
    }

}