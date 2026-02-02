<?php $ArrProfileInfo = fnGetUserLoggedInfo(1);
//print_r($ArrProfileInfo); exit;
$ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($ArrProfileInfo['companyid']);
//$ArrRoleGetInfo = $this->companymodel->fnGetRoleWiseInfo($ArrProfileInfo['id'],'');
$ArrRoleGetInfo = $this->companymodel->fnGetCommonRoleWiseInfo($ArrProfileInfo['subscriber_id'],'',$ArrProfileInfo['usertype'],KN_ROLE_PERMISSION_MASTER);
//var_dump($ArrRoleGetInfos); 
$ArrRoleInfo=count($ArrRoleGetInfo)>0 ? explode(',',$ArrRoleGetInfo[0]['title']):[];
$VarDesignation = '-';
$varsubscriber_cmpnyname='';
//if(!empty($ArrProfileInfo['desgnid'])) {
    // $ObjDesignation = $this->companymodel->headerUserDesignation($ArrProfileInfo['desgnid']);
    // $VarDesignation = @$ObjDesignation->desgn;
//}
if(!empty($ArrProfileInfo['id'])) {
    $ObjDesignation = $this->companymodel->headerUserDesignationNew($ArrProfileInfo['id']);
    $VarDesignation = @$ObjDesignation->designation;
}
if(!empty($ArrProfileInfo['subscriber_id'])){
    $varsubscriberinfo=$this->companymodel->headerusercompanyname($ArrProfileInfo['subscriber_id']); 
    $varsubscriber_cmpnyname=@$varsubscriberinfo->companyname;
    // var_dump($varsubscriber_cmpnyname);
}
$VarUserType = $ArrProfileInfo['usertype'];
$VarProfilePermission = $ArrProfileInfo['pp'];
?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/admin.css">
<header class="main-header">
    <a href="<?php echo base_url() ?>" class="logo">
        <?php //echo COMPANYNAME ?></a>
    <nav class="main-header navbar navbar-expand navbar-light navbar-blue-royal" role="navigation">
        <!-- Sidebar toggle button-->
        <span style="padding: 20px; float: left;font-size:14px; display: inline-flex" class="badge-royal-blue">|<span class="pl-4 pr-4"><?php echo @$ArrCompanyRes[0]['companyname']; ?></span>|</span>
        <div class="navbar-custom-menu" >
            <ul class="nav navbar-nav navbar-blue-royal profile_how">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle text-white profile_how" data-toggle="dropdown">
                        <?php
                        if (isset($ArrProfileInfo['usertype'])) { ?>
                        |<span class="pr-4 pl-4 text-center">
                         <?php echo $varsubscriber_cmpnyname; ?>
                        </span><?php } ?>
                        |<span class="pr-4 pl-4 text-center">
                        <?php
                        if (isset($ArrProfileInfo['usertype'])) {
                            $ArrUt = unserialize(ARRUSERTYPE);
                            echo $ArrUt[$ArrProfileInfo['usertype']].(!empty($ArrProfileInfo['dept_usercount'])?' - '.$ArrProfileInfo['dept_usercount']:'');
                        }
                        ?></span>
                        |<span class="pr-4 pl-4 text-center"><?php echo @$ArrProfileInfo['name']; ?></span>|
                        <span class="pr-4 pl-4 text-center"><?php echo $VarDesignation; ?></span>|
                        <?php
                        if (@$ArrProfileInfo['pimg'] <> '') { ?>
                            <img src="<?php echo base_url(); ?>uploads/employee/profile/<?php echo @$ArrProfileInfo['pimg'] ?>"
                                 class="user-image" alt="User Image">
                        <?php }
                        else { ?>
                            <img src="<?php echo base_url(); ?>assets/img/avatar5.png" class="user-image" style="margin-right: 15px; float: none !important;"
                                 alt="User Image">
                        <?php } ?>

                        <span class="hidden-xs" style="padding-right: 2px">


                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- commented by me on 08_02_23 
                            <li class="user-header">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php if (@$ArrProfileInfo['pimg'] <> '') { ?>
                                <img src="<?php echo base_url(); ?>uploads/employee/profile/<?php echo @$ArrProfileInfo['pimg'] ?>"
                                     class="img-circle" alt="User Image">
                            <?php } else { ?>
                                <img src="<?php echo base_url(); ?>assets/img/avatar5.png" class="img-circle"
                                     alt="User Image">
                                <!-- <i class="fa fa-user text-white"></i> 
                            <?php } ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <p>
                                <?php if (@$ArrProfileInfo['usertype'] == 1) { ?>
                                    <!--<a href="<?php echo base_url() ?>crm/profile/myprofile/" class="btn btn-default btn-flat">My Profile</a>
                                <?php } ?>
                            </p>
                        </li>-->
                        <li class="user-body hide">
                            <!--				<div class="col-xs-12 text-center">-->
                            <!--				  <a href="#">Change Password</a>-->
                            <!--				</div>-->
                            <!--<div class="col-xs-6 text-center"><a href="<?php /*echo base_url()*/ ?>company/dashboard/planBillingDetails">Plan & Billing Details</a></div>-->
                            <?php // at last called file echo base_url('profile') ?>
                            <div class="text-center"><a class="font-12" style="color:#022B61!important" href="<?php echo base_url('profile/view') ?>">User
                                    Profile</a></div>
                        </li>
                        <li class="user-body userprofile">
                            <div class="text-center font-12" onclick="navigateto('<?php echo base_url('profile/view') ?>')">User
                            Profile</div>
                        </li>
                        
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left"><a href="<?php echo base_url('profile/changepassword/') ?>"
                                                      class="btn btn-default btn-royal-blue btn-flat">Change Password</a></div>
                            <div class="pull-right"><a href="<?php echo base_url() ?>login/signout/"
                                                       class="btn btn-default btn-royal-blue btn-flat">Sign out</a></div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<style>
    .m-0{margin:0!important}.mt-0,.my-0{margin-top:0!important}.mr-0,.mx-0{margin-left:0!important}.mb-0,.my-0{margin-bottom:0!important}.ml-0,.mx-0{margin-right:0!important}.m-1{margin:.25rem!important}.mt-1,.my-1{margin-top:.25rem!important}.mr-1,.mx-1{margin-left:.25rem!important}.mb-1,.my-1{margin-bottom:.25rem!important}.ml-1,.mx-1{margin-right:.25rem!important}.m-2{margin:.5rem!important}.mt-2,.my-2{margin-top:.5rem!important}.mr-2,.mx-2{margin-left:.5rem!important}.mb-2,.my-2{margin-bottom:.5rem!important}.ml-2,.mx-2{margin-right:.5rem!important}.m-3{margin:1rem!important}.mt-3,.my-3{margin-top:1rem!important}.mr-3,.mx-3{margin-left:1rem!important}.mb-3,.my-3{margin-bottom:1rem!important}.ml-3,.mx-3{margin-right:1rem!important}.m-4{margin:1.5rem!important}.mt-4,.my-4{margin-top:1.5rem!important}.mr-4,.mx-4{margin-left:1.5rem!important}.mb-4,.my-4{margin-bottom:1.5rem!important}.ml-4,.mx-4{margin-right:1.5rem!important}.m-5{margin:3rem!important}.mt-5,.my-5{margin-top:3rem!important}.mr-5,.mx-5{margin-left:3rem!important}.mb-5,.my-5{margin-bottom:3rem!important}.ml-5,.mx-5{margin-right:3rem!important}.p-0{padding:0!important}.pt-0,.py-0{padding-top:0!important}.pr-0,.px-0{padding-left:0!important}.pb-0,.py-0{padding-bottom:0!important}.pl-0,.px-0{padding-right:0!important}.p-1{padding:.25rem!important}.pt-1,.py-1{padding-top:.25rem!important}.pr-1,.px-1{padding-left:.25rem!important}.pb-1,.py-1{padding-bottom:.25rem!important}.pl-1,.px-1{padding-right:.25rem!important}.p-2{padding:.5rem!important}.pt-2,.py-2{padding-top:.5rem!important}.pr-2,.px-2{padding-left:.5rem!important}.pb-2,.py-2{padding-bottom:.5rem!important}.pl-2,.px-2{padding-right:.5rem!important}.p-3{padding:1rem!important}.pt-3,.py-3{padding-top:1rem!important}.pr-3,.px-3{padding-left:1rem!important}.pb-3,.py-3{padding-bottom:1rem!important}.pl-3,.px-3{padding-right:1rem!important}.p-4{padding:1.5rem!important}.pt-4,.py-4{padding-top:1.5rem!important}.pr-4,.px-4{padding-left:1.5rem!important}.pb-4,.py-4{padding-bottom:1.5rem!important}.pl-4,.px-4{padding-right:1.5rem!important}.p-5{padding:3rem!important}.pt-5,.py-5{padding-top:3rem!important}.pr-5,.px-5{padding-left:3rem!important}.pb-5,.py-5{padding-bottom:3rem!important}.pl-5,.px-5{padding-right:3rem!important}.m-n1{margin:-.25rem!important}.mt-n1,.my-n1{margin-top:-.25rem!important}.mr-n1,.mx-n1{margin-left:-.25rem!important}.mb-n1,.my-n1{margin-bottom:-.25rem!important}.ml-n1,.mx-n1{margin-right:-.25rem!important}.m-n2{margin:-.5rem!important}.mt-n2,.my-n2{margin-top:-.5rem!important}.mr-n2,.mx-n2{margin-left:-.5rem!important}.mb-n2,.my-n2{margin-bottom:-.5rem!important}.ml-n2,.mx-n2{margin-right:-.5rem!important}.m-n3{margin:-1rem!important}.mt-n3,.my-n3{margin-top:-1rem!important}.mr-n3,.mx-n3{margin-left:-1rem!important}.mb-n3,.my-n3{margin-bottom:-1rem!important}.ml-n3,.mx-n3{margin-right:-1rem!important}.m-n4{margin:-1.5rem!important}.mt-n4,.my-n4{margin-top:-1.5rem!important}.mr-n4,.mx-n4{margin-left:-1.5rem!important}.mb-n4,.my-n4{margin-bottom:-1.5rem!important}.ml-n4,.mx-n4{margin-right:-1.5rem!important}.m-n5{margin:-3rem!important}.mt-n5,.my-n5{margin-top:-3rem!important}.mr-n5,.mx-n5{margin-left:-3rem!important}.mb-n5,.my-n5{margin-bottom:-3rem!important}.ml-n5,.mx-n5{margin-right:-3rem!important}.m-auto{margin:auto!important}.mt-auto,.my-auto{margin-top:auto!important}.mr-auto,.mx-auto{margin-left:auto!important}.mb-auto,.my-auto{margin-bottom:auto!important}.ml-auto,.mx-auto{margin-right:auto!important}@media (min-width:576px){.m-sm-0{margin:0!important}.mt-sm-0,.my-sm-0{margin-top:0!important}.mr-sm-0,.mx-sm-0{margin-left:0!important}.mb-sm-0,.my-sm-0{margin-bottom:0!important}.ml-sm-0,.mx-sm-0{margin-right:0!important}.m-sm-1{margin:.25rem!important}.mt-sm-1,.my-sm-1{margin-top:.25rem!important}.mr-sm-1,.mx-sm-1{margin-left:.25rem!important}.mb-sm-1,.my-sm-1{margin-bottom:.25rem!important}.ml-sm-1,.mx-sm-1{margin-right:.25rem!important}.m-sm-2{margin:.5rem!important}.mt-sm-2,.my-sm-2{margin-top:.5rem!important}.mr-sm-2,.mx-sm-2{margin-left:.5rem!important}.mb-sm-2,.my-sm-2{margin-bottom:.5rem!important}.ml-sm-2,.mx-sm-2{margin-right:.5rem!important}.m-sm-3{margin:1rem!important}.mt-sm-3,.my-sm-3{margin-top:1rem!important}.mr-sm-3,.mx-sm-3{margin-left:1rem!important}.mb-sm-3,.my-sm-3{margin-bottom:1rem!important}.ml-sm-3,.mx-sm-3{margin-right:1rem!important}.m-sm-4{margin:1.5rem!important}.mt-sm-4,.my-sm-4{margin-top:1.5rem!important}.mr-sm-4,.mx-sm-4{margin-left:1.5rem!important}.mb-sm-4,.my-sm-4{margin-bottom:1.5rem!important}.ml-sm-4,.mx-sm-4{margin-right:1.5rem!important}.m-sm-5{margin:3rem!important}.mt-sm-5,.my-sm-5{margin-top:3rem!important}.mr-sm-5,.mx-sm-5{margin-left:3rem!important}.mb-sm-5,.my-sm-5{margin-bottom:3rem!important}.ml-sm-5,.mx-sm-5{margin-right:3rem!important}.p-sm-0{padding:0!important}.pt-sm-0,.py-sm-0{padding-top:0!important}.pr-sm-0,.px-sm-0{padding-left:0!important}.pb-sm-0,.py-sm-0{padding-bottom:0!important}.pl-sm-0,.px-sm-0{padding-right:0!important}.p-sm-1{padding:.25rem!important}.pt-sm-1,.py-sm-1{padding-top:.25rem!important}.pr-sm-1,.px-sm-1{padding-left:.25rem!important}.pb-sm-1,.py-sm-1{padding-bottom:.25rem!important}.pl-sm-1,.px-sm-1{padding-right:.25rem!important}.p-sm-2{padding:.5rem!important}.pt-sm-2,.py-sm-2{padding-top:.5rem!important}.pr-sm-2,.px-sm-2{padding-left:.5rem!important}.pb-sm-2,.py-sm-2{padding-bottom:.5rem!important}.pl-sm-2,.px-sm-2{padding-right:.5rem!important}.p-sm-3{padding:1rem!important}.pt-sm-3,.py-sm-3{padding-top:1rem!important}.pr-sm-3,.px-sm-3{padding-left:1rem!important}.pb-sm-3,.py-sm-3{padding-bottom:1rem!important}.pl-sm-3,.px-sm-3{padding-right:1rem!important}.p-sm-4{padding:1.5rem!important}.pt-sm-4,.py-sm-4{padding-top:1.5rem!important}.pr-sm-4,.px-sm-4{padding-left:1.5rem!important}.pb-sm-4,.py-sm-4{padding-bottom:1.5rem!important}.pl-sm-4,.px-sm-4{padding-right:1.5rem!important}.p-sm-5{padding:3rem!important}.pt-sm-5,.py-sm-5{padding-top:3rem!important}.pr-sm-5,.px-sm-5{padding-left:3rem!important}.pb-sm-5,.py-sm-5{padding-bottom:3rem!important}.pl-sm-5,.px-sm-5{padding-right:3rem!important}.m-sm-n1{margin:-.25rem!important}.mt-sm-n1,.my-sm-n1{margin-top:-.25rem!important}.mr-sm-n1,.mx-sm-n1{margin-left:-.25rem!important}.mb-sm-n1,.my-sm-n1{margin-bottom:-.25rem!important}.ml-sm-n1,.mx-sm-n1{margin-right:-.25rem!important}.m-sm-n2{margin:-.5rem!important}.mt-sm-n2,.my-sm-n2{margin-top:-.5rem!important}.mr-sm-n2,.mx-sm-n2{margin-left:-.5rem!important}.mb-sm-n2,.my-sm-n2{margin-bottom:-.5rem!important}.ml-sm-n2,.mx-sm-n2{margin-right:-.5rem!important}.m-sm-n3{margin:-1rem!important}.mt-sm-n3,.my-sm-n3{margin-top:-1rem!important}.mr-sm-n3,.mx-sm-n3{margin-left:-1rem!important}.mb-sm-n3,.my-sm-n3{margin-bottom:-1rem!important}.ml-sm-n3,.mx-sm-n3{margin-right:-1rem!important}.m-sm-n4{margin:-1.5rem!important}.mt-sm-n4,.my-sm-n4{margin-top:-1.5rem!important}.mr-sm-n4,.mx-sm-n4{margin-left:-1.5rem!important}.mb-sm-n4,.my-sm-n4{margin-bottom:-1.5rem!important}.ml-sm-n4,.mx-sm-n4{margin-right:-1.5rem!important}.m-sm-n5{margin:-3rem!important}.mt-sm-n5,.my-sm-n5{margin-top:-3rem!important}.mr-sm-n5,.mx-sm-n5{margin-left:-3rem!important}.mb-sm-n5,.my-sm-n5{margin-bottom:-3rem!important}.ml-sm-n5,.mx-sm-n5{margin-right:-3rem!important}.m-sm-auto{margin:auto!important}.mt-sm-auto,.my-sm-auto{margin-top:auto!important}.mr-sm-auto,.mx-sm-auto{margin-left:auto!important}.mb-sm-auto,.my-sm-auto{margin-bottom:auto!important}.ml-sm-auto,.mx-sm-auto{margin-right:auto!important}}@media (min-width:768px){.m-md-0{margin:0!important}.mt-md-0,.my-md-0{margin-top:0!important}.mr-md-0,.mx-md-0{margin-left:0!important}.mb-md-0,.my-md-0{margin-bottom:0!important}.ml-md-0,.mx-md-0{margin-right:0!important}.m-md-1{margin:.25rem!important}.mt-md-1,.my-md-1{margin-top:.25rem!important}.mr-md-1,.mx-md-1{margin-left:.25rem!important}.mb-md-1,.my-md-1{margin-bottom:.25rem!important}.ml-md-1,.mx-md-1{margin-right:.25rem!important}.m-md-2{margin:.5rem!important}.mt-md-2,.my-md-2{margin-top:.5rem!important}.mr-md-2,.mx-md-2{margin-left:.5rem!important}.mb-md-2,.my-md-2{margin-bottom:.5rem!important}.ml-md-2,.mx-md-2{margin-right:.5rem!important}.m-md-3{margin:1rem!important}.mt-md-3,.my-md-3{margin-top:1rem!important}.mr-md-3,.mx-md-3{margin-left:1rem!important}.mb-md-3,.my-md-3{margin-bottom:1rem!important}.ml-md-3,.mx-md-3{margin-right:1rem!important}.m-md-4{margin:1.5rem!important}.mt-md-4,.my-md-4{margin-top:1.5rem!important}.mr-md-4,.mx-md-4{margin-left:1.5rem!important}.mb-md-4,.my-md-4{margin-bottom:1.5rem!important}.ml-md-4,.mx-md-4{margin-right:1.5rem!important}.m-md-5{margin:3rem!important}.mt-md-5,.my-md-5{margin-top:3rem!important}.mr-md-5,.mx-md-5{margin-left:3rem!important}.mb-md-5,.my-md-5{margin-bottom:3rem!important}.ml-md-5,.mx-md-5{margin-right:3rem!important}.p-md-0{padding:0!important}.pt-md-0,.py-md-0{padding-top:0!important}.pr-md-0,.px-md-0{padding-left:0!important}.pb-md-0,.py-md-0{padding-bottom:0!important}.pl-md-0,.px-md-0{padding-right:0!important}.p-md-1{padding:.25rem!important}.pt-md-1,.py-md-1{padding-top:.25rem!important}.pr-md-1,.px-md-1{padding-left:.25rem!important}.pb-md-1,.py-md-1{padding-bottom:.25rem!important}.pl-md-1,.px-md-1{padding-right:.25rem!important}.p-md-2{padding:.5rem!important}.pt-md-2,.py-md-2{padding-top:.5rem!important}.pr-md-2,.px-md-2{padding-left:.5rem!important}.pb-md-2,.py-md-2{padding-bottom:.5rem!important}.pl-md-2,.px-md-2{padding-right:.5rem!important}.p-md-3{padding:1rem!important}.pt-md-3,.py-md-3{padding-top:1rem!important}.pr-md-3,.px-md-3{padding-left:1rem!important}.pb-md-3,.py-md-3{padding-bottom:1rem!important}.pl-md-3,.px-md-3{padding-right:1rem!important}.p-md-4{padding:1.5rem!important}.pt-md-4,.py-md-4{padding-top:1.5rem!important}.pr-md-4,.px-md-4{padding-left:1.5rem!important}.pb-md-4,.py-md-4{padding-bottom:1.5rem!important}.pl-md-4,.px-md-4{padding-right:1.5rem!important}.p-md-5{padding:3rem!important}.pt-md-5,.py-md-5{padding-top:3rem!important}.pr-md-5,.px-md-5{padding-left:3rem!important}.pb-md-5,.py-md-5{padding-bottom:3rem!important}.pl-md-5,.px-md-5{padding-right:3rem!important}.m-md-n1{margin:-.25rem!important}.mt-md-n1,.my-md-n1{margin-top:-.25rem!important}.mr-md-n1,.mx-md-n1{margin-left:-.25rem!important}.mb-md-n1,.my-md-n1{margin-bottom:-.25rem!important}.ml-md-n1,.mx-md-n1{margin-right:-.25rem!important}.m-md-n2{margin:-.5rem!important}.mt-md-n2,.my-md-n2{margin-top:-.5rem!important}.mr-md-n2,.mx-md-n2{margin-left:-.5rem!important}.mb-md-n2,.my-md-n2{margin-bottom:-.5rem!important}.ml-md-n2,.mx-md-n2{margin-right:-.5rem!important}.m-md-n3{margin:-1rem!important}.mt-md-n3,.my-md-n3{margin-top:-1rem!important}.mr-md-n3,.mx-md-n3{margin-left:-1rem!important}.mb-md-n3,.my-md-n3{margin-bottom:-1rem!important}.ml-md-n3,.mx-md-n3{margin-right:-1rem!important}.m-md-n4{margin:-1.5rem!important}.mt-md-n4,.my-md-n4{margin-top:-1.5rem!important}.mr-md-n4,.mx-md-n4{margin-left:-1.5rem!important}.mb-md-n4,.my-md-n4{margin-bottom:-1.5rem!important}.ml-md-n4,.mx-md-n4{margin-right:-1.5rem!important}.m-md-n5{margin:-3rem!important}.mt-md-n5,.my-md-n5{margin-top:-3rem!important}.mr-md-n5,.mx-md-n5{margin-left:-3rem!important}.mb-md-n5,.my-md-n5{margin-bottom:-3rem!important}.ml-md-n5,.mx-md-n5{margin-right:-3rem!important}.m-md-auto{margin:auto!important}.mt-md-auto,.my-md-auto{margin-top:auto!important}.mr-md-auto,.mx-md-auto{margin-left:auto!important}.mb-md-auto,.my-md-auto{margin-bottom:auto!important}.ml-md-auto,.mx-md-auto{margin-right:auto!important}}@media (min-width:992px){.m-lg-0{margin:0!important}.mt-lg-0,.my-lg-0{margin-top:0!important}.mr-lg-0,.mx-lg-0{margin-left:0!important}.mb-lg-0,.my-lg-0{margin-bottom:0!important}.ml-lg-0,.mx-lg-0{margin-right:0!important}.m-lg-1{margin:.25rem!important}.mt-lg-1,.my-lg-1{margin-top:.25rem!important}.mr-lg-1,.mx-lg-1{margin-left:.25rem!important}.mb-lg-1,.my-lg-1{margin-bottom:.25rem!important}.ml-lg-1,.mx-lg-1{margin-right:.25rem!important}.m-lg-2{margin:.5rem!important}.mt-lg-2,.my-lg-2{margin-top:.5rem!important}.mr-lg-2,.mx-lg-2{margin-left:.5rem!important}.mb-lg-2,.my-lg-2{margin-bottom:.5rem!important}.ml-lg-2,.mx-lg-2{margin-right:.5rem!important}.m-lg-3{margin:1rem!important}.mt-lg-3,.my-lg-3{margin-top:1rem!important}.mr-lg-3,.mx-lg-3{margin-left:1rem!important}.mb-lg-3,.my-lg-3{margin-bottom:1rem!important}.ml-lg-3,.mx-lg-3{margin-right:1rem!important}.m-lg-4{margin:1.5rem!important}.mt-lg-4,.my-lg-4{margin-top:1.5rem!important}.mr-lg-4,.mx-lg-4{margin-left:1.5rem!important}.mb-lg-4,.my-lg-4{margin-bottom:1.5rem!important}.ml-lg-4,.mx-lg-4{margin-right:1.5rem!important}.m-lg-5{margin:3rem!important}.mt-lg-5,.my-lg-5{margin-top:3rem!important}.mr-lg-5,.mx-lg-5{margin-left:3rem!important}.mb-lg-5,.my-lg-5{margin-bottom:3rem!important}.ml-lg-5,.mx-lg-5{margin-right:3rem!important}.p-lg-0{padding:0!important}.pt-lg-0,.py-lg-0{padding-top:0!important}.pr-lg-0,.px-lg-0{padding-left:0!important}.pb-lg-0,.py-lg-0{padding-bottom:0!important}.pl-lg-0,.px-lg-0{padding-right:0!important}.p-lg-1{padding:.25rem!important}.pt-lg-1,.py-lg-1{padding-top:.25rem!important}.pr-lg-1,.px-lg-1{padding-left:.25rem!important}.pb-lg-1,.py-lg-1{padding-bottom:.25rem!important}.pl-lg-1,.px-lg-1{padding-right:.25rem!important}.p-lg-2{padding:.5rem!important}.pt-lg-2,.py-lg-2{padding-top:.5rem!important}.pr-lg-2,.px-lg-2{padding-left:.5rem!important}.pb-lg-2,.py-lg-2{padding-bottom:.5rem!important}.pl-lg-2,.px-lg-2{padding-right:.5rem!important}.p-lg-3{padding:1rem!important}.pt-lg-3,.py-lg-3{padding-top:1rem!important}.pr-lg-3,.px-lg-3{padding-left:1rem!important}.pb-lg-3,.py-lg-3{padding-bottom:1rem!important}.pl-lg-3,.px-lg-3{padding-right:1rem!important}.p-lg-4{padding:1.5rem!important}.pt-lg-4,.py-lg-4{padding-top:1.5rem!important}.pr-lg-4,.px-lg-4{padding-left:1.5rem!important}.pb-lg-4,.py-lg-4{padding-bottom:1.5rem!important}.pl-lg-4,.px-lg-4{padding-right:1.5rem!important}.p-lg-5{padding:3rem!important}.pt-lg-5,.py-lg-5{padding-top:3rem!important}.pr-lg-5,.px-lg-5{padding-left:3rem!important}.pb-lg-5,.py-lg-5{padding-bottom:3rem!important}.pl-lg-5,.px-lg-5{padding-right:3rem!important}.m-lg-n1{margin:-.25rem!important}.mt-lg-n1,.my-lg-n1{margin-top:-.25rem!important}.mr-lg-n1,.mx-lg-n1{margin-left:-.25rem!important}.mb-lg-n1,.my-lg-n1{margin-bottom:-.25rem!important}.ml-lg-n1,.mx-lg-n1{margin-right:-.25rem!important}.m-lg-n2{margin:-.5rem!important}.mt-lg-n2,.my-lg-n2{margin-top:-.5rem!important}.mr-lg-n2,.mx-lg-n2{margin-left:-.5rem!important}.mb-lg-n2,.my-lg-n2{margin-bottom:-.5rem!important}.ml-lg-n2,.mx-lg-n2{margin-right:-.5rem!important}.m-lg-n3{margin:-1rem!important}.mt-lg-n3,.my-lg-n3{margin-top:-1rem!important}.mr-lg-n3,.mx-lg-n3{margin-left:-1rem!important}.mb-lg-n3,.my-lg-n3{margin-bottom:-1rem!important}.ml-lg-n3,.mx-lg-n3{margin-right:-1rem!important}.m-lg-n4{margin:-1.5rem!important}.mt-lg-n4,.my-lg-n4{margin-top:-1.5rem!important}.mr-lg-n4,.mx-lg-n4{margin-left:-1.5rem!important}.mb-lg-n4,.my-lg-n4{margin-bottom:-1.5rem!important}.ml-lg-n4,.mx-lg-n4{margin-right:-1.5rem!important}.m-lg-n5{margin:-3rem!important}.mt-lg-n5,.my-lg-n5{margin-top:-3rem!important}.mr-lg-n5,.mx-lg-n5{margin-left:-3rem!important}.mb-lg-n5,.my-lg-n5{margin-bottom:-3rem!important}.ml-lg-n5,.mx-lg-n5{margin-right:-3rem!important}.m-lg-auto{margin:auto!important}.mt-lg-auto,.my-lg-auto{margin-top:auto!important}.mr-lg-auto,.mx-lg-auto{margin-left:auto!important}.mb-lg-auto,.my-lg-auto{margin-bottom:auto!important}.ml-lg-auto,.mx-lg-auto{margin-right:auto!important}}@media (min-width:1200px){.m-xl-0{margin:0!important}.mt-xl-0,.my-xl-0{margin-top:0!important}.mr-xl-0,.mx-xl-0{margin-left:0!important}.mb-xl-0,.my-xl-0{margin-bottom:0!important}.ml-xl-0,.mx-xl-0{margin-right:0!important}.m-xl-1{margin:.25rem!important}.mt-xl-1,.my-xl-1{margin-top:.25rem!important}.mr-xl-1,.mx-xl-1{margin-left:.25rem!important}.mb-xl-1,.my-xl-1{margin-bottom:.25rem!important}.ml-xl-1,.mx-xl-1{margin-right:.25rem!important}.m-xl-2{margin:.5rem!important}.mt-xl-2,.my-xl-2{margin-top:.5rem!important}.mr-xl-2,.mx-xl-2{margin-left:.5rem!important}.mb-xl-2,.my-xl-2{margin-bottom:.5rem!important}.ml-xl-2,.mx-xl-2{margin-right:.5rem!important}.m-xl-3{margin:1rem!important}.mt-xl-3,.my-xl-3{margin-top:1rem!important}.mr-xl-3,.mx-xl-3{margin-left:1rem!important}.mb-xl-3,.my-xl-3{margin-bottom:1rem!important}.ml-xl-3,.mx-xl-3{margin-right:1rem!important}.m-xl-4{margin:1.5rem!important}.mt-xl-4,.my-xl-4{margin-top:1.5rem!important}.mr-xl-4,.mx-xl-4{margin-left:1.5rem!important}.mb-xl-4,.my-xl-4{margin-bottom:1.5rem!important}.ml-xl-4,.mx-xl-4{margin-right:1.5rem!important}.m-xl-5{margin:3rem!important}.mt-xl-5,.my-xl-5{margin-top:3rem!important}.mr-xl-5,.mx-xl-5{margin-left:3rem!important}.mb-xl-5,.my-xl-5{margin-bottom:3rem!important}.ml-xl-5,.mx-xl-5{margin-right:3rem!important}.p-xl-0{padding:0!important}.pt-xl-0,.py-xl-0{padding-top:0!important}.pr-xl-0,.px-xl-0{padding-left:0!important}.pb-xl-0,.py-xl-0{padding-bottom:0!important}.pl-xl-0,.px-xl-0{padding-right:0!important}.p-xl-1{padding:.25rem!important}.pt-xl-1,.py-xl-1{padding-top:.25rem!important}.pr-xl-1,.px-xl-1{padding-left:.25rem!important}.pb-xl-1,.py-xl-1{padding-bottom:.25rem!important}.pl-xl-1,.px-xl-1{padding-right:.25rem!important}.p-xl-2{padding:.5rem!important}.pt-xl-2,.py-xl-2{padding-top:.5rem!important}.pr-xl-2,.px-xl-2{padding-left:.5rem!important}.pb-xl-2,.py-xl-2{padding-bottom:.5rem!important}.pl-xl-2,.px-xl-2{padding-right:.5rem!important}.p-xl-3{padding:1rem!important}.pt-xl-3,.py-xl-3{padding-top:1rem!important}.pr-xl-3,.px-xl-3{padding-left:1rem!important}.pb-xl-3,.py-xl-3{padding-bottom:1rem!important}.pl-xl-3,.px-xl-3{padding-right:1rem!important}.p-xl-4{padding:1.5rem!important}.pt-xl-4,.py-xl-4{padding-top:1.5rem!important}.pr-xl-4,.px-xl-4{padding-left:1.5rem!important}.pb-xl-4,.py-xl-4{padding-bottom:1.5rem!important}.pl-xl-4,.px-xl-4{padding-right:1.5rem!important}.p-xl-5{padding:3rem!important}.pt-xl-5,.py-xl-5{padding-top:3rem!important}.pr-xl-5,.px-xl-5{padding-left:3rem!important}.pb-xl-5,.py-xl-5{padding-bottom:3rem!important}.pl-xl-5,.px-xl-5{padding-right:3rem!important}.m-xl-n1{margin:-.25rem!important}.mt-xl-n1,.my-xl-n1{margin-top:-.25rem!important}.mr-xl-n1,.mx-xl-n1{margin-left:-.25rem!important}.mb-xl-n1,.my-xl-n1{margin-bottom:-.25rem!important}.ml-xl-n1,.mx-xl-n1{margin-right:-.25rem!important}.m-xl-n2{margin:-.5rem!important}.mt-xl-n2,.my-xl-n2{margin-top:-.5rem!important}.mr-xl-n2,.mx-xl-n2{margin-left:-.5rem!important}.mb-xl-n2,.my-xl-n2{margin-bottom:-.5rem!important}.ml-xl-n2,.mx-xl-n2{margin-right:-.5rem!important}.m-xl-n3{margin:-1rem!important}.mt-xl-n3,.my-xl-n3{margin-top:-1rem!important}.mr-xl-n3,.mx-xl-n3{margin-left:-1rem!important}.mb-xl-n3,.my-xl-n3{margin-bottom:-1rem!important}.ml-xl-n3,.mx-xl-n3{margin-right:-1rem!important}.m-xl-n4{margin:-1.5rem!important}.mt-xl-n4,.my-xl-n4{margin-top:-1.5rem!important}.mr-xl-n4,.mx-xl-n4{margin-left:-1.5rem!important}.mb-xl-n4,.my-xl-n4{margin-bottom:-1.5rem!important}.ml-xl-n4,.mx-xl-n4{margin-right:-1.5rem!important}.m-xl-n5{margin:-3rem!important}.mt-xl-n5,.my-xl-n5{margin-top:-3rem!important}.mr-xl-n5,.mx-xl-n5{margin-left:-3rem!important}.mb-xl-n5,.my-xl-n5{margin-bottom:-3rem!important}.ml-xl-n5,.mx-xl-n5{margin-right:-3rem!important}.m-xl-auto{margin:auto!important}.mt-xl-auto,.my-xl-auto{margin-top:auto!important}.mr-xl-auto,.mx-xl-auto{margin-left:auto!important}.mb-xl-auto,.my-xl-auto{margin-bottom:auto!important}.ml-xl-auto,.mx-xl-auto{margin-right:auto!important}}
</style>
<style>
     h1.firstHeading{
         margin-top:100px!important;
     }
    .navbar-custom-menu > .navbar-nav > li > .dropdown-menu{
        /*left: 257px!important;*/
        top: 100px!important;
        border-radius:3px!important;
        /*right:25px;*/
        right:15px;
     }
    .user-footer{
        border:2px solid #fff!important;
     }
    .user-body{
        border: 2px solid #fff!important;
        border-bottom-right-radius:4px!important;
        border-bottom-left-radius:4px!important;
        border-top-right-radius:4px!important;
        border-top-left-radius:4px!important;
     }
    .user-body:hover{
       background-color: #f9f9f9!important;
    }
    .userprofile{
        color: #022B61!important;
        background-color:#ebecec;
    }
    .userprofile:hover{
        color: #fff!important;
        background-color: #022B61!important;
    }
    .font-12 {
        font-size:12px!important;
        cursor:pointer;
    }
    .profile_how:hover{
        background-color: #022B61 !important;
    }
    .nav .open > a, .nav .open > a:focus, .nav .open > a:hover {
        background-color: #022B61;
        color: #ffffff;
        border-color: #337ab7;
    }
    .navbar-blue-royal {
        background-color: #022B61;
    }
    .navbar-default .navbar-nav > li > a {
        color: #022B61;
        font-size: 12px !important;

    }
    .dropdown-menu > li > a {
        padding: 6px 12px;
        border-radius: 1px;
    }
    .dropdown-menu > li > a:hover {
        background-color: #d5d5d5;
    }
    .container-fluid {
        background-color: #EBECEC;
    }

    .gray_header{
        background-color: #D0D1D1;
        color: #022B61;
    }
    .navbar{
        min-height: 10px!important;
        margin-bottom: 0;
    }
    .menu-nav > li > a {
        line-height: 5px !important;
    }
    .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > .active > a:hover {
        color: #011f49;
        background-color: #d5d5d5;
    }

    .bgn-white{
        background-color: white;
    }

    .open > .dropdown-menu {
        font-size: 12px !important;
        padding:3px!important;
    }
    .managemaster{
        /*columns: 6;*/
        /*left:-545px;*/
        columns: 5;
        left:-425px;
        /*left:-531px;*/
    }
    .manageuser{
        columns: 2;
        left:0;
    }
    /*------------------------------------
    - COLOR royal-blue
    ------------------------------------*/
    .alert-royal-blue {
        color: #000000;
        background-color: #3489fa;
        border-color: #207efa;
    }

    .alert-royal-blue hr {
        border-top-color: #076ff9;
    }

    .alert-royal-blue .alert-link {
        color: #000000;
    }

    .badge-royal-blue {
        color: #fff;
        background-color: #022B61;
    }

    .badge-royal-blue[href]:hover, .badge-royal-blue[href]:focus {
        color: #fff;
        background-color: #00142f;
    }

    .bg-royal-blue {
        background-color: #022B61 !important;
    }

    a.bg-royal-blue:hover, a.bg-royal-blue:focus,
    button.bg-royal-blue:hover,
    button.bg-royal-blue:focus {
        background-color: #00142f !important;
    }

    .border-royal-blue {
        border-color: #022B61 !important;
    }

    .btn-royal-blue {
        color: #022B61!important;
        background-color:#ebecec;
        border-color: #D0D1D1;
        font-size:12px!important;
    }
    .btn-royal-blue:hover {
        color: #fff!important;
        background-color: #022B61;
        border-color: #00142f;
    }
    
    /*.btn-royal-blue:focus, .btn-royal-blue.focus {*/
    /*    box-shadow: 0 0 0 0.1rem rgba(2, 43, 97, 0.5);*/
    /*}*/
    .btn-royal-blue-secondary {
        color: #022B61;
        background-color: transparent;
        border-color: #093065;
    }
    .btn-royal-blue-secondary:hover {
        color: #ffffff;
        background-color: #022B61;
        border-color: #093065;
        box-shadow: 0 0 0 0.2rem rgba(2, 43, 97, 0.5);
    }

    .btn-royal-blue.disabled, .btn-royal-blue:disabled {
        color: #fff;
        background-color: #022B61;
        border-color: #022B61;
    }

    .btn-royal-blue:not(:disabled):not(.disabled):active, .btn-royal-blue:not(:disabled):not(.disabled).active, .show > .btn-royal-blue.dropdown-toggle {
        color: #fff;
        background-color: #00142f;
        border-color: #000e20;
    }

    /*.btn-royal-blue:not(:disabled):not(.disabled):active:focus, .btn-royal-blue:not(:disabled):not(.disabled).active:focus, .show > .btn-royal-blue.dropdown-toggle:focus {*/
    /*    box-shadow: 0 0 0 0.2rem rgba(2, 43, 97, 0.5);*/
    /*}*/
    .btn.active.focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn:active:focus, .btn:focus{
        outline:none!important;
    }
    .btn-royal-blue-secondary
    .btn-outline-royal-blue_one {
        color: #022B61;
        background-color: transparent;
        border-color: #022B61;
    }

    .btn-outline-royal-blue {
        color: #022B61;
        background-color: transparent;
        border-color: #022B61;
    }

    .btn-outline-royal-blue:hover {
        color: #fff;
        background-color: #003B87;
        border-color: rgba(2, 43, 97, 0.71);
    }

    .btn-outline-royal-blue:focus, .btn-outline-royal-blue.focus {
        box-shadow: 0 0 0 0.2rem rgba(2, 43, 97, 0.5);
    }

    .btn-outline-royal-blue.disabled, .btn-outline-royal-blue:disabled {
        color: #022B61;
        background-color: transparent;
    }

    .btn-outline-royal-blue:not(:disabled):not(.disabled):active, .btn-outline-royal-blue:not(:disabled):not(.disabled).active, .show > .btn-outline-royal-blue.dropdown-toggle {
        color: #fff;
        background-color: #022B61;
        border-color: #022B61;
    }

    .btn-outline-royal-blue:not(:disabled):not(.disabled):active:focus, .btn-outline-royal-blue:not(:disabled):not(.disabled).active:focus, .show > .btn-outline-royal-blue.dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(2, 43, 97, 0.5);
    }

    .List-group-item-royal-blue {
        color: #000000;
        background-color: #207efa;
    }

    .list-group-item-royal-blue.list-group-item-action:hover, .list-group-item-royal-blue.list-group-item-action:focus {
        color: #000000;
        background-color: #076ff9;
    }

    .list-group-item-royal-blue.list-group-item-action.active {
        color: #fff;
        background-color: #000000;
        border-color: #000000;
    }

    .table-royal-blue,
    .table-royal-blue > th,
    .table-royal-blue > td {
        background-color: #207efa;
    }

    .table-hover .table-royal-blue:hover {
        background-color: #076ff9;
    }

    .table-hover .table-royal-blue:hover > td,
    .table-hover .table-royal-blue:hover > th {
        background-color: #076ff9;
    }

    .text-royal-blue {
        font-weight: 600;
         /*color: #022B61 !important; */
         color: #0036ae !important; 
        /*color: #000000 !important;*/
    }

    a.text-royal-blue:hover, a.text-royal-blue:focus {
        color: #00142f !important;
    }
    
    .text-to-black {
        
         color: #022B61 !important; 
    }

    a.text-to-black:hover, a.text-to-black:focus {
        color: #00142f !important;
    }

.dropdown-submenu {
  position: relative;
}

.dropdown-submenu>.dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -6px;
  margin-left: -1px;
  -webkit-border-radius: 0 6px 6px 6px;
  -moz-border-radius: 0 6px 6px;
  border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
  display: block;
}

/*.dropdown-submenu>a:after {*/
/*  display: block;*/
/*  content: " ";*/
/*  float: right;*/
/*  width: 0;*/
/*  height: 0;*/
/*  border-color: transparent;*/
/*  border-style: solid;*/
/*  border-width: 5px 0 5px 5px;*/
/*  border-left-color: #ccc;*/
/*  margin-top: 5px;*/
/*  margin-right: -10px;*/
/*}*/
.carets{
  /*display: inline-block;*/
  /*  width: 0;*/
  /*  height: 0;*/
  /*  margin-left: 2px;*/
  /*  vertical-align: middle;*/
  /*  border-top: 4px dashed;*/
  /*  border-top: 4px solid \9;*/
  /*  border-right: 4px solid transparent;*/
  /*  border-left: 4px solid transparent*/
  
  display: block;
  content: " ";
  float: right;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 4px 0 4px 4px;
  border-left-color: #022B61;
  margin-top: 5px;
  margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
  border-left-color: #fff;
}
.active > a >.carets{
    border-left-color: #fff!important;
}
 .active > a:hover >.carets{
    border-left-color: #fff!important;
}
.dropdown-submenu.pull-left {
  float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
  left: -100%;
  margin-left: 10px;
  -webkit-border-radius: 6px 0 6px 6px;
  -moz-border-radius: 6px 0 6px 6px;
  border-radius: 6px 0 6px 6px;
}
@media (min-width: 1200px) {
    .container {
        width: 900px!important;
    }
}
</style>
<nav class="navbar navbar-default navbar-header" >
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <?php 
            // print_r(fnGetUserLoggedInfo('1')); 
            $userInfo = fnGetUserLoggedInfo('1');
            $userType = $userInfo['usertype'];
        ?>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="menu-nav nav navbar-nav pull-right">
                <?php if(count($ArrRoleInfo)>0 && in_array('Manage Master Data',$ArrRoleInfo)) {?>
                <li class="nav-item dropdown    <?php echo ($this->uri->segment(2)=='macceptancelevel' || $this->uri->segment(2)=='mbom' || $this->uri->segment(2)=='bom1'
                                                    || $this->uri->segment(2)=='bom' || $this->uri->segment(2)=='mbomvendor' || $this->uri->segment(2)=='mbrand' 
                                                    || $this->uri->segment(2)=='brand' || $this->uri->segment(2)=='mastersetup' || $this->uri->segment(2)=='mlogistics'
                                                    || $this->uri->segment(2)=='Magent' || $this->uri->segment(2)=='mcolormatchstd' || $this->uri->segment(2)=='mconsignor' 
                                                    || $this->uri->segment(2)=='mconsignee' || $this->uri->segment(2)=='mdsr' || $this->uri->segment(2)=='mdyeingvendor' 
                                                    || $this->uri->segment(2)=='mjobwrk' || $this->uri->segment(2)=='membellishment' || $this->uri->segment(2)=='membellishmentvendor' 
                                                    || $this->uri->segment(2)=='fabricblend' || $this->uri->segment(2)=='fabriccontent' || $this->uri->segment(2)=='fabricname' 
                                                    || $this->uri->segment(2)=='mfabricfinishwet_dry' || $this->uri->segment(2)=='mfabvendor' || $this->uri->segment(2)=='mgarmentsampling' 
                                                    || $this->uri->segment(2)=='mgpd' || $this->uri->segment(2)=='mlab' || $this->uri->segment(2)=='mpackingmaterial' 
                                                    || $this->uri->segment(2)=='mpackingcode' || $this->uri->segment(2)=='mport' || $this->uri->segment(2)=='mprocessflow' 
                                                    || $this->uri->segment(2)=='myarnblend' || $this->uri->segment(2)=='myarncontent' || $this->uri->segment(2)=='myarncount' 
                                                    || $this->uri->segment(2)=='myarnsplreq' || $this->uri->segment(2)=='myarnvendor' || $this->uri->segment(2)=='yarnvendor' 
                                                    || $this->uri->segment(2)=='mauth' || $this->uri->segment(2)=='mtestauth' || $this->uri->segment(2)=='mtypemedium'
                                                )?'active':''?>">
                        <!--<a onclick="AddClass('mydiv')"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Master Data</a>-->
                     <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Master Data <span class="caret"></span></a>
                          <ul class="dropdown-menu managemaster">
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='macceptancelevel')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>macceptancelevel/manage">Acceptance Level</a></li>
                            <li class="nav-item hide <?php echo ($this->uri->segment(2)=='mbom')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mbom/manage/">BOM (Art - 1) - Item Description (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='bom1' && $this->uri->segment(3)=='Bominstrumenttype')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>bom1/Bominstrumenttype/manage/">BOM (Art - 1) - Item Description</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='bom1' && $this->uri->segment(3)=='Bomblend')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>bom1/Bomblend/manage/">BOM (Art - 1) - Blend (%)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='bom1' && $this->uri->segment(3)=='Bomcontent')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>bom1/Bomcontent/manage/">BOM (Art - 1) - Content</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='bom1' && $this->uri->segment(3)=='Bommaterial')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>bom1/Bommaterial/manage/">BOM (Art - 1) - Material</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='bom' && $this->uri->segment(3)=='Bominstrumenttype')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>bom/Bominstrumenttype/manage/">BOM (Art - 2) - Item Description</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='bom' && $this->uri->segment(3)=='Bomblend')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomblend/manage/">BOM (Art - 2) - Blend (%)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='bom' && $this->uri->segment(3)=='Bomcontent')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomcontent/manage/">BOM (Art - 2) - Content</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='bom' && $this->uri->segment(3)=='Bommaterial')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>bom/Bommaterial/manage/">BOM (Art - 2) - Material</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mbomvendor')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mbomvendor/managebomvendor/">BOM Vendor Details</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mbrand')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mbrand/manage/">Brand (Remove)</a></li>
                            <!--<li class="nav-item"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mbuyer/manage/">Buyer</a></li>-->
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='brand')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>brand/manage/">Brand</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mastersetup' && $this->uri->segment(3)=='managechecklist')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mastersetup/managechecklist/">Checklist</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mlogistics' && $this->uri->segment(3)=='clearingAgent')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/clearingAgent">Clearing Agent (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='Magent' && ($this->uri->segment(3)=='manage' || $this->uri->segment(3)=='addedit'))?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>Magent/manage">Clearing Agent</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mcolormatchstd')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mcolormatchstd/manage">Colour Matching Standards (Remove)</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mconsignor')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mconsignor/manageconsignor">Consignor (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='Magent' && ($this->uri->segment(3)=='consignor' || $this->uri->segment(3)=='addeditconsignor'))?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>Magent/consignor">Consignor</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mconsignee')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mconsignee/manageconsignee">Consignee (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='Magent' && ($this->uri->segment(3)=='consignee' || $this->uri->segment(3)=='addeditconsignee'))?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>Magent/consignee">Consignee</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mdsr')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mdsr/managedsr/">Dyeing Special Request</a>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mdyeingvendor')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mdyeingvendor/managedyeingvendor/">Dyeing Vendor Details (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mjobwrk' && ($this->uri->segment(3)=='mdyeing' || $this->uri->segment(3)=='addedit'))?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mjobwrk/mdyeing">Dyeing Job Work Details</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='membellishment' && ($this->uri->segment(3)=='manageType' || $this->uri->segment(3)=='addeditembellishmenttype'))?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>membellishment/manageType">Embellishment - Type</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='membellishment' && ($this->uri->segment(3)=='manageMediumMaterial' || $this->uri->segment(3)=='addeditmediummaterial'))?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>membellishment/manageMediumMaterial">Embellishment - Medium / Material</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='membellishmentvendor')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>membellishmentvendor/manageembellishmentvendor">Embellishment Vendor Details (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mjobwrk' && ($this->uri->segment(3)=='membelish' || $this->uri->segment(3)=='addeditembl'))?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mjobwrk/membelish">Embellishment Job Work Details</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='fabricblend')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>fabricblend/manage/">Fabric - Blend (%)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='fabriccontent')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>fabriccontent/manage/">Fabric - Content</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='fabricname')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>fabricname/manage/">Fabric - Name</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mfabricfinishwet_dry')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mfabricfinishwet_dry/manage">Fabric Finish Wet / Dry</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mfabvendor')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mfabvendor/manage">Fabric vendor Details</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mlogistics' && $this->uri->segment(3)=='forwardingAgent')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/forwardingAgent">Forwarding Agent (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='Magent' && ($this->uri->segment(3)=='forwarding' || $this->uri->segment(3)=='fwdaddedit'))?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>Magent/forwarding">Forwarding Agent</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mgarmentsampling')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mgarmentsampling/manage/">Garment Sample Requirement (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mgpd')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mgpd/managegpd/">Garment Parts</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mlogistics' && $this->uri->segment(3)=='importer')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/importer">Importer (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='Magent' && ($this->uri->segment(3)=='importer' || $this->uri->segment(3)=='importaddedit'))?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>Magent/importer">Importer</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mlab')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mlab/managelab/">Lab</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mastersetup' && $this->uri->segment(3)=='managemodeofenquiry')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mastersetup/managemodeofenquiry/">Mode of Enquiry</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mpackingmaterial')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mpackingmaterial/managepackingmaterial/">Packing Material (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mpackingcode')?'active':'';?> "><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mpackingcode/managepackingcode/">Packing Code</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mport')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mport/manageport/">Port</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mprocessflow')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mprocessflow/manageprocessflow/">Garment Process Flow</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='myarnblend')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>myarnblend/manage/">Yarn - Blend (%)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='myarncontent')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>myarncontent/manage/">Yarn - Content</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='myarncount')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>myarncount/manage/">Yarn - Count</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='myarnsplreq')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>myarnsplreq/manage">Yarn Spec. Request</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='myarnvendor')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>myarnvendor/manage">Yarn Vendor Details (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='yarnvendor')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>yarnvendor/manage">Yarn Vendor Details</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mauth')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mauth/managetauth">Testing Authority (Remove)</a></li>
                            <li class="nav-item <?php echo ($this->uri->segment(2)=='mtestauth')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mtestauth/manage">Lab Testing Authority</a></li>
                            <li class="nav-item hide<?php echo ($this->uri->segment(2)=='mtypemedium')?'active':'';?>"><a class="nav-link" href="<?php echo base_url() . CNFCOMPANY ?>mtypemedium/manage/">Type or Medium (Remove)</a></li>
                        </ul>
                     </li>
                     <?php } ?>
                      <?php if(count($ArrRoleInfo)>0 && in_array('Manage Department & User',$ArrRoleInfo)) {?>
                     <li class="nav-item dropdown <?php echo ($this->uri->segment(2)=='muser')?'active':''?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Department & User <span class="caret"></span></a>
                              <ul class="dropdown-menu manageuser">
                                <li class="nav-item <?php echo ($this->uri->segment(2)=='muser')?'active':'';?>"><a class="nav-link" href="<?php echo base_url( CNFCOMPANY.'muser/manage') ?>">User</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url( CNFCOMPANY.'mcaduser/manage') ?>">CAD Dept. List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY . 'mdocandlocuser/manage') ?>"> Documentation and Logistics Dept. List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY . 'mfabricuser/manage') ?>">Fabric Dept. List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/manage') ?>">Finance Dept. List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY.'mlabuser/manage') ?>">Lab Dept. List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY.'mmanagementuser/manage'); ?>">Management List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY.'mmerchantuser/manage'); ?>">Merchant List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY . 'mproductionuser/manage') ?>"> Production Dept. LIST</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY.'mpurchaseuser/manage') ?>">Purchase Dept. List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY.'mqausers/manage') ?>">Quality assurance Dept. List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY.'msamplinguser/manage') ?>">Sampling Dept. List</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(CNFCOMPANY.'mstoreuser/manage') ?>">Stores Dept. List</a></li>
                            </ul>
                     </li>
                     <?php } ?>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Team <span class="caret"></span></a>
                              <ul class="dropdown-menu">
                               <li class="nav-item"><a class="nav-link" href="<?php echo base_url('company/merteam/teamList'); ?>">Team List</a></li>
                            </ul>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Roles <span class="caret"></span></a>
                              <ul class="dropdown-menu">
                               <li class="nav-item"><a class="nav-link" href="<?php echo base_url('management/assignroles'); ?>">Management Roles</a></li>
                            </ul>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Log-in Credentials <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('dashboard/manageDesignations'); ?>">Manage Designation</a></li>
                        </ul>
                     </li>
                </ul>
             <ul class="menu-nav nav navbar-nav pull-right hide">
                    <li class="nav-item dropdown">
                        <!--<a onclick="AddClass('mydiv')"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Master Data</a>-->
                     <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Master Data</a>
                      <div class="dropdown-menu" >
                        <div class="container m-2" >
                          <ul class="col-md-3">
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>macceptancelevel/manage">Acceptance Level</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbom/manage/">BOM (Art - 1) - Item Description</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomblend/manage/">BOM (Art - 1) - Blend (%)</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomcontent/manage/">BOM (Art - 1) - Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bommaterial/manage/">BOM (Art - 1) - Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbomvendor/managebomvendor/">BOM Vendor Details</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbrand/manage/">Brand</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbuyer/manage/">Buyer</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bominstrumenttype/manage/">BOM (Art - 2) - Item Description</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomblend/manage/">BOM (Art - 2) - Blend (%)</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomcontent/manage/">BOM (Art - 2) - Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bommaterial/manage/">BOM (Art - 2) - Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mastersetup/managechecklist/">Checklist</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mcolormatchstd/manage">Colour Matching Standard</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mdsr/managedsr/">Dyeing Special Request</a>
                        </ul>
                        <ul class="col-md-3">
                            
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mdyeingvendor/managedyeingvendor/">Dyeing Vendor Details</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>membellishment/manageType">Embellishment - Type</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>membellishment/manageMediumMaterial">Embellishment - Medium / Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>membellishmentvendor/manageembellishmentvendor">Embellishment Vendor Details</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mastersetup/managemodeofenquiry/">Mode of Enquiry</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>fabricblend/manage/">Fabric - Blend (%)</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>fabriccontent/manage/">Fabric - Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>fabricname/manage/">Fabric - Name</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mfabricfinishwet_dry/manage">Fabric Finish Wet / Dry</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mgarmentsampling/manage/">Garment Sample Requirement</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mgpd/managegpd/">Garment Parts</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlab/managelab/">Lab</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/forwardingAgent">Forwarding Agent</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/clearingAgent">Clearing Agent</a></li>
                        </ul>
                        <ul class="col-md-3">
                            
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/importer">Importer</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mconsignor/manageconsignor">Consignor</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mconsignee/manageconsignee">Consignee</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mpackingmaterial/managepackingmaterial/">Packing Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mpackingcode/managepackingcode/">Packing Code</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mprocessflow/manageprocessflow/">Process Flow</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mport/manageport/">Port</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mauth/managetauth">Testing Authority</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarnsplreq/manage">Yarn Spec. Request</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarnvendor/manage">Yarn Vendor Details</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarnblend/manage/">Yarn - Blend (%)</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarncontent/manage/">Yarn - Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarncount/manage/">Yarn - Count</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mtypemedium/manage/">Type or Medium</a></li>

                                </ul>
                        </div>
                        
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage User</a>
                        <div class="dropdown-menu" >
                            <div class="container m-2" >
                              <ul class="col-md-4">
                                <li><a href="<?php echo base_url( CNFCOMPANY.'mcaduser/manage') ?>">CAD Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY . 'mdocandlocuser/manage') ?>"> Documentation and Logistics Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY . 'mfabricuser/manage') ?>">Fabric Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/manage') ?>">Finance Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mlabuser/manage') ?>">Lab Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mmanagementuser/manage'); ?>">Management List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mmerchantuser/manage'); ?>">Merchant List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mpurchaseuser/manage') ?>">Purchase Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mqausers/manage') ?>">Quality assurance Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/manage') ?>">Sampling Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mstoreuser/manage') ?>">Stores Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY . 'mproductionuser/manage') ?>"> Production Dept. LIST</a></li>
                            </ul>
                            </div>
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Team</a>
                        <div class="dropdown-menu" >
                            <div class="container m-2" >
                              <ul class="col-md-4">
                               <li><a href="<?php echo base_url('company/merteam/teamList'); ?>">Team List</a></li>
                            </ul>
                            </div>
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Roles</a>
                        <div class="dropdown-menu" >
                            <div class="container m-2" >
                              <ul class="col-md-4">
                               <li><a href="<?php echo base_url('management/assignroles'); ?>">Management Roles</a></li>
                            </ul>
                            </div>
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Password</a>
                     </li>
                </ul>
             <ul class="menu-nav nav navbar-nav pull-right hide">
                     <li class="nav-item dropdown">
                      <a onclick="AddClass('mydiv')"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Master Data</a>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" onclick="RemoveClass('mydiv')" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage User</a>
                        <div class="dropdown-menu" >
                            <div class="container m-2" >
                              <ul class="col-md-4">
                                <li><a href="<?php echo base_url( CNFCOMPANY.'mcaduser/manage') ?>">CAD Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY . 'mdocandlocuser/manage') ?>"> Documentation and Logistics Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY . 'mfabricuser/manage') ?>">Fabric Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/manage') ?>">Finance Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mlabuser/manage') ?>">Lab Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mmanagementuser/manage'); ?>">Management List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mmerchantuser/manage'); ?>">Merchant List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mpurchaseuser/manage') ?>">Purchase Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mqausers/manage') ?>">Quality assurance Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/manage') ?>">Sampling Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY.'mstoreuser/manage') ?>">Stores Dept. List</a></li>
                                <li><a href="<?php echo base_url(CNFCOMPANY . 'mproductionuser/manage') ?>"> Production Dept. LIST</a></li>
                            </ul>
                            </div>
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" onclick="RemoveClass('mydiv')" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Team</a>
                        <div class="dropdown-menu" >
                            <div class="container m-2" >
                              <ul class="col-md-4">
                               <li><a href="<?php echo base_url('company/merteam/teamList'); ?>">Team List</a></li>
                            </ul>
                            </div>
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" onclick="RemoveClass('mydiv')" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Roles</a>
                        <div class="dropdown-menu" >
                            <div class="container m-2" >
                              <ul class="col-md-4">
                               <li><a href="<?php echo base_url('management/assignroles'); ?>">Management Roles</a></li>
                            </ul>
                            </div>
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Manage Password</a>
                     </li>
                </ul>    
            
        </div><!-- /.navbar-collapse -->
    </div>
    <div class="row col-md-12 dropdown-menu" id="mydiv" style="display: none;">
        <div class="col-md-4">
            <ul class="">
                       <li><a href="<?php echo base_url() . CNFCOMPANY ?>macceptancelevel/manage">Acceptance Level</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbom/manage/">BOM (Art - 1) - Item Description</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomblend/manage/">BOM (Art - 1) - Blend (%)</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomcontent/manage/">BOM (Art - 1) - Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bommaterial/manage/">BOM (Art - 1) - Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbomvendor/managebomvendor/">BOM Vendor Details</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbrand/manage/">Brand</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mbuyer/manage/">Buyer</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bominstrumenttype/manage/">BOM (Art - 2) - Item Description</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomblend/manage/">BOM (Art - 2) - Blend (%)</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bomcontent/manage/">BOM (Art - 2) - Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>bom/Bommaterial/manage/">BOM (Art - 2) - Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mastersetup/managechecklist/">Checklist</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mcolormatchstd/manage">Colour Matching Standard</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mdsr/managedsr/">Dyeing Special Request</a>
                    </ul>
        </div>
        <div class="col-md-4">
             <ul class="">
                        <li><a href="<?php echo base_url() . CNFCOMPANY ?>mdyeingvendor/managedyeingvendor/">Dyeing Vendor Details</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>membellishment/manageType">Embellishment - Type</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>membellishment/manageMediumMaterial">Embellishment - Medium / Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>membellishmentvendor/manageembellishmentvendor">Embellishment Vendor Details</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mastersetup/managemodeofenquiry/">Mode of Enquiry</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>fabricblend/manage/">Fabric - Blend (%)</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>fabriccontent/manage/">Fabric - Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>fabricname/manage/">Fabric - Name</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mfabricfinishwet_dry/manage">Fabric Finish Wet / Dry</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mgarmentsampling/manage/">Garment Sample Requirement</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mgpd/managegpd/">Garment Parts</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlab/managelab/">Lab</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/forwardingAgent">Forwarding Agent</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/clearingAgent">Clearing Agent</a></li>
                    </ul>
        </div>
        <div class="col-md-4">
             <ul class="">
                       <li><a href="<?php echo base_url() . CNFCOMPANY ?>mlogistics/importer">Importer</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mconsignor/manageconsignor">Consignor</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mconsignee/manageconsignee">Consignee</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mpackingmaterial/managepackingmaterial/">Packing Material</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mpackingcode/managepackingcode/">Packing Code</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mprocessflow/manageprocessflow/">Process Flow</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mport/manageport/">Port</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mauth/managetauth">Testing Authority</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarnsplreq/manage">Yarn Spec. Request</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarnvendor/manage">Yarn Vendor Details</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarnblend/manage/">Yarn - Blend (%)</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarncontent/manage/">Yarn - Content</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>myarncount/manage/">Yarn - Count</a></li>
                            <li><a href="<?php echo base_url() . CNFCOMPANY ?>mtypemedium/manage/">Type or Medium</a></li>

                    </ul>
        </div>
    </div>
</nav>
<script>
function AddClass(id) {
  var element = document.getElementById(id);
  $('#'+id).toggle();
}
function RemoveClass(id) {
  var element = document.getElementById(id);
  $('#'+id).hide();
}

</script>
<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>