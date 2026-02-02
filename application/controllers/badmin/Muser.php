<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Muser extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->model('Badminusermodel');
        $this->load->helper('xssclean');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->usertype = $ArrUserLoggedInfo['usertype'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->limit = LIMITPERPAGE;
         $this->ArrDbCols = array('usertype', 'dept_usercount','designation','contactname ','username ','email_id','mobile', 'status', 'updatedby','dateupdated');
    }

    public function addedit()
    {
        // $VarRemainingUser = $this->commonmodel->remaininguseravailable($this->companyid,1);
        // if($VarRemainingUser == 0) {
        //     die('User Limit Ended. Can\'t add more users');
        // }
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }

        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->Badminusermodel->fnGetInfo('','',$VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
        } else {
           
        }
        $this->load->view(CNFBADMIN . 'addedit_user', $ArrData);
    }
    
    public function addedit_user()
    {
        
        $ArrData = array('ArrBasicInfo' => array(), 'VarId' => '');
        $VarHashId = $this->uri->segment(4);
        $ArrData['Edit'] = $this->uri->segment(5);
        
        $VarUserInfo = fnGetUserLoggedInfo(1);
        if (isset($VarUserInfo['companyid'])) {
            $VarCompanyId = $VarUserInfo['companyid'];
        }

        if ($VarHashId <> '' && base64_decode(urldecode($VarHashId))) {
            $VarId = base64_decode(urldecode($VarHashId));
            $ArrResults = $this->Badminusermodel->fnGetInfo('','',$VarId);
            $ArrRoleInfo=$this->Badminusermodel->fnGetRoleInfo($VarId,'');
            $ArrData['ArrRoleInfo'] = count($ArrRoleInfo)>0 ? explode(',',$ArrRoleInfo[0]['title']):[];
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
            $ArrData['ArrUserType'] = unserialize(BARRUSERTYPE);
            $usertypes=$this->commonmodel->getUserType($VarId); //getting usertype based on userid
        } else {
           
        }
        if(!empty($usertypes) && ($usertypes->usertype==0)){
          $this->load->view(CNFBADMIN . 'badmin_dept', $ArrData);  
        }else if(!empty($usertypes) && ($usertypes->usertype==16)){
          $this->load->view(CNFBADMIN . 'market_dept', $ArrData);  
        }else{
            $this->manage();
        }
        
    }
    
    public function updateusertInfo() {
        $ArrUpdateData = array();
        $ArrUpdateData['id'] = xssclean($this->input->post('id'));
        $ArrUpdateData['usertype'] = xssclean($this->input->post('dept')); // usertype nothing but department_id
        $ArrUpdateData['dept_usercount'] = xssclean($this->input->post('deptcount'));
        $ArrUpdateData['designation'] = xssclean($this->input->post('dsgn'));
        $ArrUpdateData['companyid'] = $this->companyid;
       // $ArrUpdateData['designation'] = xssclean($this->input->post('dsgn'));
        $ArrUpdateData['contactname'] = xssclean($this->input->post('username'));
        $ArrUpdateData['address'] = xssclean($this->input->post('addr'));
        $ArrUpdateData['username'] = xssclean($this->input->post('loginid'));
        $ArrUpdateData['password'] = xssclean($this->input->post('pwd'));
        $ArrUpdateData['email_id'] = xssclean($this->input->post('em'));
        $ArrUpdateData['mobile'] = xssclean($this->input->post('mbno'));
        $ArrUpdateData['doj'] = date('Y-m-d', strtotime($this->input->post('doj')));
        $ArrUpdateData['curr_salarypackage'] = xssclean($this->input->post('curr_salpckg'));
        $ArrUpdateData['bankname'] = xssclean($this->input->post('bnk'));
        $ArrUpdateData['accountname'] = xssclean($this->input->post('actn'));
        $ArrUpdateData['accountno'] = xssclean($this->input->post('actno'));
        $ArrUpdateData['ifsccode'] = xssclean($this->input->post('ifsc'));
        $ArrUpdateData['swiftcode'] = xssclean($this->input->post('swift'));
        $ArrUpdateData['status'] = xssclean($this->input->post('s'));
        $ArrUpdateData['dateupdated'] = $this->mysqldatetime;
        $ArrUpdateData['updatedby'] = $this->userid;
        $ArrUpdateData['profilepermission'] =  xssclean($this->input->post('dept'));
        if (!empty($ArrUpdateData['username'])) {
            if (empty($ArrUpdateData['id'])) {
                $ArrUpdateData['pin'] = '1234';
                $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
            }
            // Note:|| $this->input->post('dept')==15 this condition included on below line for planning dept
            if($this->input->post('dept')==3 || $this->input->post('dept')==15){
                $ArrResult = $this->Badminusermodel->saveMerchantUser($ArrUpdateData);
            }else{
                $ArrResult = $this->Badminusermodel->saveUser($ArrUpdateData);
            }
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
     //////////////newly added////////////////
    public function manage()
    {
        $VarFrom = xssclean($this->input->post('rfrom'));
        $VarDept =trim(xssclean($this->input->post('dept')));
        $VarUsername =trim(xssclean($this->input->post('contactname')));
        $VarDesgn=trim(xssclean($this->input->post('dsgn')));
        $VarLoginid=trim(xssclean($this->input->post('loginid')));
        $VarEmail_id = trim(xssclean($this->input->post('em')));
        $VarMobno =xssclean(preg_replace('/^\s+/', '+ ',  trim($this->input->post('mobno'))));
        $VarStatus = xssclean($this->input->post('s'));
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder = xssclean($this->input->post('sortorder'));
        $VarAfilter = xssclean($this->input->post('afilter'));
        $ArrDbCols = array('usertype', 'dept_usercount','designation','contactname ','username ','email_id','mobile', 'status', 'dateupdated');
        if ($VarFrom == 1) {

            $VarURLSegment = 4;

            $this->load->library('pagination');

            $config['base_url'] = base_url() . CNFBADMIN . 'muser/manage/';

            $config['total_rows'] = $this->Badminusermodel->fnCount($VarDept,$VarUsername,$VarDesgn,$VarLoginid,$VarEmail_id,$VarMobno,$VarStatus);
            $config['per_page'] = $this->limit;

            $config['uri_segment'] = $VarURLSegment;

            $offset = $this->uri->segment($VarURLSegment);

            $this->pagination->initialize($config);

            //$sortby = "dateupdated";
              $sortby = "log";

            $sortorder = "desc";
            if ($clickedColumnId <> '' && $newsortorder <> '') {

                if (array_key_exists($clickedColumnId, $ArrDbCols)) {

                    $sortby = $ArrDbCols[$clickedColumnId];

                }

                $sortorder = $newsortorder;

            }


            $ArrList = $this->Badminusermodel->fnList($VarDept,$VarUsername,$VarDesgn,$VarLoginid,$VarEmail_id,$VarMobno,$VarStatus,$this->limit, $offset, $sortby, $sortorder, $VarAfilter);

            $data['pagination'] = $this->pagination->create_linkswithajax('Badminuser');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);
            $ArrUserType = unserialize(BARRUSERTYPE);
          
            foreach ($ArrList['listData'] as $ObjUnit) {
                
                $ArrFnlList[$i]['id'] = $ObjUnit->id;
                $ArrFnlList[$i]['dept'] = $ArrUserType[$ObjUnit->usertype];
                $ArrFnlList[$i]['usercount'] =!empty($ObjUnit->dept_usercount)?$ObjUnit->dept_usercount:'';
                $ArrFnlList[$i]['desgn'] =!empty($ObjUnit->designation)?$ObjUnit->designation:'';
                $ArrFnlList[$i]['username']=!empty($ObjUnit->contactname)?$ObjUnit->contactname:'';
                $ArrFnlList[$i]['loginid'] =!empty($ObjUnit->username)?$ObjUnit->username:'';
                $ArrFnlList[$i]['em'] =!empty($ObjUnit->email_id)?$ObjUnit->email_id:'';
                $ArrFnlList[$i]['mobno'] =!empty($ObjUnit->mobile)?$ObjUnit->mobile :'';
                $ArrFnlList[$i]['s'] = $ArrStatus[$ObjUnit->status];
                //$ArrFnlList[$i]['ub'] = !empty($ObjUnit->updatedby)?$ArrList['updatedByData'][$ObjUnit->updatedby]:'';
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->updatedby)?$ArrList['updatedByData'][$ObjUnit->updatedby]:'';
                
                $ArrFnlList[$i]['du'] =($ObjUnit->dateupdated!='0000-00-00 00:00:00')? date('d/m/Y h:i A', strtotime($ObjUnit->log)):'';
                
                
                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {
            
            $this->load->view(CNFBADMIN . 'manage_user');

        }

    }
    
    function changeStatus() {
        $VarRfrom = xssclean($this->input->post('rfrom'));
        if ($VarRfrom == 1) {
            $VarStatus = xssclean($this->input->post('status'));
            $VarCid = xssclean($this->input->post('cid'));
            if ($VarStatus <> '' && $VarCid <> '') {
                $Arrids = json_decode($VarCid, true);
                $this->db->where_in('id', $Arrids);
                if ($this->db->update(KN_MUSERS, array('status' => $VarStatus))) {
                    echo json_encode(array('errcode' => 1));
                }
                return false;
            }
            die;
        }
    }
    
    public function getDeptcount()
    {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if ($VarFrom == 1) {
            $Vardeptid = xssclean($this->input->post('dept_id'));
            $Res = $this->Badminusermodel->fnCount($Vardeptid);
            echo json_encode(array('usercnt' => $Res));
        }
    }
    public function updateInfo() { // regards role permission
        $ArrUpdateData = array();
        $ArrUpdateData['userid'] = xssclean($this->input->post('userid'));
        $ArrUpdateData['dateupdated'] = $this->mysqldatetime;
        $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
        $ArrUpdateData['companyid'] = $this->companyid;
        $ArrUpdateData['updatedby'] = $this->userid;
        $ArrUpdateData['status'] = 1;
        $title = xssclean($this->input->post('title')); // form names
        //var_dump(explode(',',$title));die;
        if (!empty($title)) {
            $ArrCheckExist = $this->fnCheck($ArrUpdateData['userid']);
            if (!empty($ArrCheckExist)) {
                $this->db->delete(KN_USERROLE_PERMISSION, array('userid' => $ArrUpdateData['userid']));
            }
            $selected_title_array=explode(',',$title); // from multicheckbox
              
            foreach ($selected_title_array as $k=>$v){
                $ArrUpdateData['title']=$v;
                $this->db->insert(KN_USERROLE_PERMISSION, $ArrUpdateData);
             
            }
            
            $ArrResult['errcode'] = 1;
            $ArrResult['msg'] = 'Saved';
        } else {
            $ArrResult['errcode'] = -1;
            $ArrResult['msg'] = 'Invalid Input';
        }
        echo json_encode($ArrResult);
    }
    function fnCheck($VarId = '') {
        $this->db->from(KN_USERROLE_PERMISSION);
        if ($VarId <> '') {
            $this->db->where_in('userid', array($VarId));
        }
        $countAll = $this->db->count_all_results();
        return $countAll;
    }
    
}