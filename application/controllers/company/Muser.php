<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Muser extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('commonmodel');
        $this->load->model('companymodel');
        $this->load->model('Musermodel');
        $this->load->model(CNFBADMIN . "subscriptionmodal");
        $this->load->helper('xssclean');
        $ArrUserLoggedInfo = fnGetUserLoggedInfo('1');
        $this->companyid = $ArrUserLoggedInfo['companyid'];
        $this->usertype = $ArrUserLoggedInfo['usertype'];
        $this->userid = $ArrUserLoggedInfo['id'];
        $this->subscriber_id = $ArrUserLoggedInfo['subscriber_id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
        $this->limit = LIMITPERPAGE;
         $this->ArrDbCols = array('usertype', 'dept_usercount','designation','contactname ','username ','email_id','mobile', 'status', 'updatedby','dateupdated');
    }

    public function addedit()
    {
        //as per client need commented regard this for user admin also on 14/09/23 (below 4 lines)
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
            $ArrResults = $this->Musermodel->fnGetInfo('','',$VarId);
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
           
        }else {
           
        }   $usertype=array();
            $subscriberinfo = $this->subscriptionmodal->fngetsubscriptionlist($this->subscriber_id);
            $ArrRoleInfo = $this->companymodel->fnGetCommonRoleWiseInfo($this->subscriber_id,'', '' ,KN_ROLE_PERMISSION_MASTER);  
            $Arrusertype = $this->subscriptionmodal->fngetusertype($this->subscriber_id, '' ,KN_MNGUSERDEPTCNT,1);  
            $ArrUserTypes = unserialize(ARRUSERTYPE);
            // var_dump($Arrusertype);
            // Initialize an empty array to store matched key-value pairs
            $matchedArray = array();
            foreach($Arrusertype as $k=>$v){
               // Check if the key exists in the arrUserType array
                if (array_key_exists($v['usertype'], $ArrUserTypes)) {
                    // If key exists, add the key-value pair to matchedArray
                    $matchedArray[$v['usertype']] = $ArrUserTypes[$v['usertype']];
                }
              
            }
            $proforma_id=$subscriberinfo[0]['proforma_id'];
            $loginid=!empty($ArrResults[0])? explode('@',$ArrResults[0]['username']):'';
            $ArrData['loginsuffix'] =  !empty($loginid)? '@'.$loginid[1]:'@'.substr($subscriberinfo[0]['subscriber_refno'], 11);
            $ArrData['loginprefix'] =  !empty($loginid)?$loginid[0]:'';
            $ArrData['ARRSUBUSERTYPE'] =  !empty($matchedArray)?$matchedArray:$ArrUserTypes;
            $ArrData['subscriber_id'] =  !empty($this->subscriber_id)?$this->subscriber_id:'';
            $ArrData['proforma_id'] =  !empty($proforma_id)?$proforma_id:'';
        if(!empty($VarUserInfo['subscriber_id'])){
          $this->load->view(CNFCOMPANY . 'addedit_subscriber', $ArrData);  
        }else{
          $this->load->view(CNFCOMPANY . 'addedit_user', $ArrData);  
        }
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
            $ArrResults = $this->Musermodel->fnGetInfo('','',$VarId);
            if(!empty($this->subscriber_id)){
              $ArrRoleInfo = $this->companymodel->fnGetCommonRoleWiseInfo($this->subscriber_id,'', $this->usertype ,KN_ROLE_PERMISSION_MASTER);  
            }else{
              $ArrRoleInfo=$this->Musermodel->fnGetRoleInfo($VarId,'');  
            }
            $ArrData['ArrRoleInfo'] = count($ArrRoleInfo)>0 ? explode(',',$ArrRoleInfo[0]['title']):[];
            $ArrData['ArrBasicInfo'] = $ArrResults[0];
            $ArrData['VarId'] = $ArrResults[0]['id'];
            $ArrData['ArrUserType'] = unserialize(ARRUSERTYPE);
            $usertypes=$this->commonmodel->getUserType($VarId); //getting usertype based on userid
        } else {
           
        }
        if(!empty($usertypes) && ($usertypes->usertype==3 || $usertypes->usertype==15)){
          $this->load->view(CNFCOMPANY . 'merchant_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==2){
          $this->load->view(CNFCOMPANY . 'manage_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==4){
          $this->load->view(CNFCOMPANY . 'cad_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==5){
          $this->load->view(CNFCOMPANY . 'sample_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==12){
          $this->load->view(CNFCOMPANY . 'finance_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==7){
          $this->load->view(CNFCOMPANY . 'purchase_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==8){
          $this->load->view(CNFCOMPANY . 'bom_store_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==11){
          $this->load->view(CNFCOMPANY . 'qa_dept', $ArrData);  
        }else if(!empty($usertypes) && $usertypes->usertype==1){
          $this->load->view(CNFCOMPANY . 'companyadmin_dept', $ArrData);  
        }else{
            $this->manage();
        }
        
    }
    
    public function updateusertInfo() {
        $ArrUpdateData = array();
        $ArrUpdateData['id'] = xssclean($this->input->post('id'));
        $ArrUpdateData['subscriber_id'] = xssclean($this->input->post('subscriber_id'));
        $ArrUpdateData['proforma_id'] = xssclean($this->input->post('proforma_id'));
        $ArrUpdateData['usertype'] = xssclean($this->input->post('dept')); // usertype nothing but department_id
        $ArrUpdateData['dept_usercount'] = xssclean($this->input->post('deptcount'));
        $ArrUpdateData['designation'] = xssclean($this->input->post('dsgn'));
        $ArrUpdateData['companyid'] = $this->companyid;
       // $ArrUpdateData['designation'] = xssclean($this->input->post('dsgn'));
        $ArrUpdateData['contactname'] = xssclean($this->input->post('username'));
        $ArrUpdateData['address'] = xssclean($this->input->post('addr'));
        $ArrUpdateData['username'] = xssclean($this->input->post('loginid'));
        $ArrUpdateData['password'] = xssclean($this->input->post('pwd'));
         $ArrUpdateData['pin'] = xssclean($this->input->post('pin'));
        $ArrUpdateData['email_id'] = xssclean($this->input->post('em'));
        $ArrUpdateData['mobile'] = xssclean($this->input->post('mbno'));
        $ArrUpdateData['doj'] = !empty($this->input->post('doj'))? date('Y-m-d', strtotime($this->input->post('doj'))):'0000-00-00';
        $ArrUpdateData['curr_salarypackage'] = xssclean($this->input->post('curr_salpckg'));
        $ArrUpdateData['bankname'] = xssclean($this->input->post('bnk'));
        $ArrUpdateData['accountname'] = xssclean($this->input->post('actn'));
        $ArrUpdateData['accountno'] = xssclean($this->input->post('actno'));
        $ArrUpdateData['ifsccode'] = xssclean($this->input->post('ifsc'));
        $ArrUpdateData['swiftcode'] = xssclean($this->input->post('swift'));
        $ArrUpdateData['status'] = xssclean($this->input->post('s'));
        $ArrUpdateData['badminstatus'] = $this->input->post('badms', true) ?? '';
        $ArrUpdateData['dateupdated'] = $this->mysqldatetime;
        $ArrUpdateData['updatedby'] = $this->userid;
        $ArrUpdateData['profilepermission'] =  xssclean($this->input->post('dept'));
        if (!empty($ArrUpdateData['username'])) {
            if (empty($ArrUpdateData['id'])) {
                $ArrUpdateData['pin'] = '1234';
                $ArrUpdateData['datecreated'] = date('Y-m-d H:i:s');
            }
            // Note:|| $this->input->post('dept')==15 this condition included on below line for planning dept
            if($this->input->post('dept')==3 || $this->input->post('dept')==17){
                $ArrResult = $this->Musermodel->saveMerchantUser($ArrUpdateData);
            }else{
                $ArrResult = $this->Musermodel->saveUser($ArrUpdateData);
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

            $config['base_url'] = base_url() . CNFCOMPANY . 'muser/manage/';

            $config['total_rows'] = $this->Musermodel->fnCount($VarDept,$VarUsername,$VarDesgn,$VarLoginid,$VarEmail_id,$VarMobno,$VarStatus);
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


            $ArrList = $this->Musermodel->fnList($VarDept,$VarUsername,$VarDesgn,$VarLoginid,$VarEmail_id,$VarMobno,$VarStatus,$this->limit, $offset, $sortby, $sortorder, $VarAfilter);

            $data['pagination'] = $this->pagination->create_linkswithajax('Muser');

            $i = 0;

            $ArrFnlList = array();

            $ArrStatus = unserialize(ARRSTATUS);
            $ArrUserType = unserialize(ARRUSERTYPE);
          
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
                $ArrFnlList[$i]['ub'] = !empty($ObjUnit->updatedby)?$ArrList['updatedByData'][$ObjUnit->updatedby]:'';
                $ArrFnlList[$i]['du'] =($ObjUnit->dateupdated!='0000-00-00 00:00:00')? date('d-m-Y H:i A', strtotime($ObjUnit->dateupdated)):'';
                
                
                $i = $i + 1;

            }

            echo json_encode(array('errcode' => 1, 'cn' => $config['total_rows'], 'ct' => $i, 're' => $ArrFnlList, 'pa' => base64_encode($data['pagination'])));

            unset($ArrFnlList);

            die;

        } else {
            
            $this->load->view(CNFCOMPANY . 'manage_user');

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
            $Res = $this->Musermodel->fnCount($Vardeptid);
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
     public function getsubuserDeptcount()
    {
        $VarFrom = xssclean($this->input->post('rFrom'));
        if ($VarFrom == 1) {
            $Vardeptid = xssclean($this->input->post('dept_id'));
            $Varsubscriberid = xssclean($this->input->post('subscriber_id'));
            $Varproformaid = xssclean($this->input->post('proforma_id'));
            $Res = $this->Musermodel->fnsubuserCount($Vardeptid,'','','','','','1',$Varsubscriberid,$Varproformaid);
            $Restotuser= $this->subscriptionmodal->fnSubuserDeptCount($Vardeptid,$Varsubscriberid,$Varproformaid);
            //var_dump($Rescond);
            echo json_encode(array('usercnt' => $Res,'totuser'=>$Restotuser));
        }
    }
}