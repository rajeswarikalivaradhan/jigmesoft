<?php $ArrProfileInfo = fnGetUserLoggedInfo(1);
// print_r($ArrProfileInfo); exit;
$ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($ArrProfileInfo['companyid']);
$ArrRoleGetInfo =$this->companymodel->fnGetCommonRoleWiseInfo($ArrProfileInfo['subscriber_id'],'',$ArrProfileInfo['usertype'],KN_ROLE_PERMISSION_MASTER);
$subscriber_detail =$this->companymodel->subscriber_detail($ArrProfileInfo['subscriber_id']);

$ArrRoleInfo=count($ArrRoleGetInfo)>0 ? explode(',',$ArrRoleGetInfo[0]['title']):[];
$VarDesignation = '-';
if(!empty($ArrProfileInfo['id'])) {
    $ObjDesignation = $this->companymodel->headerUserDesignationNew($ArrProfileInfo['id']);
    $VarDesignation = @$ObjDesignation->designation;
}
?>
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
                        |<span class="pr-4 pl-4 text-center">
                           <?php echo $subscriber_detail->companyname; ?>
                       </span>
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
                        <li class="user-body userprofile">
                            <div class="text-center font-12" onclick="navigateto('<?php echo base_url('profile/view') ?>')">User
                            Profile</div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo base_url('profile/changepassword/') ?>" class="btn btn-default btn-royal-blue btn-flat">Change Password</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo base_url() ?>login/signout/" class="btn btn-default btn-royal-blue btn-flat">Sign out</a>
                            </div>
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
    .navbar-custom-menu > .navbar-nav > li > .dropdown-menu{
        /*left: 257px!important;*/
        top: 100px!important;
        border-radius:3px!important;
        /*right:25px;*/
        right:15px;
     }
    .user-footer{
         border-left:4px solid #fff!important;
         border-right:4px solid #fff!important;
         border-bottom:4px solid #fff!important;
     }
     .user-body{
        border: 4px solid #fff!important;
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
        font-size:12px!important;
    }
    .font-12 {
        font-size:12px!important;
        cursor:pointer;
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

    .list-group-item-royal-blue {
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
            <?php if($userType == '8') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Purchase Indent List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'purchaseindentlist' || $this->router->fetch_method() == 'purchaseindentlistBOM1' || $this->router->fetch_method() == 'purchaseindentlistBOM2' || $this->router->fetch_method() == 'storepurchaseindentdetailspi'|| $this->router->fetch_method() == 'storepiupdate'||$this->uri->segment(5) == 'purchaseindentlist') echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/purchaseindentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Purchase Indent List</span></span>                  
                        </a> -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Purchase Indent List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/purchaseindentlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>ALL  P. I. List.</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/purchaseindentlistBOM1') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1)  P. I. List.</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/purchaseindentlistBOM2') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2)  P. I. List.</span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Supply Closure List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'supplyclosurelist' || $this->router->fetch_method() == 'supplyclosuredetails' || $this->router->fetch_method() == 'supplyclosurelistBOM1' || $this->router->fetch_method() == 'supplyclosurelistBOM2' ||$this->router->fetch_method() == 'storepurchaseindentdetails1' ) echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/supplyclosurelist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Supply Closure List</span></span>                  
                        </a> -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Supply Closure List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/supplyclosurelist') ?>" class="nav-link">
                                       <span class="nav-text"><span>ALL Supply Closure List.</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/supplyclosurelistBOM1') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1)  Supply Closure List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/supplyclosurelistBOM2') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2)  Supply Closure List</span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('M.I. List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'mireceivedlist' || $this->router->fetch_method() == 'mipendinglist' ||$this->router->fetch_method() == 'mireceiveddetails' ||$this->router->fetch_method() == 'miDraftDc' ||$this->router->fetch_method() == 'miissuedlist' || $this->router->fetch_method() == 'mipartpendinglist'
                            ) echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">M.I. List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/mireceivedlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>M.I. List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/mipendinglist') ?>" class="nav-link">
                                       <span class="nav-text"><span>M.I. Pending List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/mipartpendinglist') ?>" class="nav-link">
                                       <span class="nav-text"><span>M.I. Issued  Part List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/miissuedlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>M.I. Issued Full List</span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Order Stock List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'itemList' || $this->router->fetch_method() == 'itemListBOM1' || $this->router->fetch_method() == 'itemListBOM2'||$this->uri->segment(5) == 'orderstockdetails' ||$this->router->fetch_method() == 'orderstockdetails' ) echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/itemList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Order Stock List</span></span>                  
                        </a> -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Order Stock List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/itemList') ?>" class="nav-link">
                                       <span class="nav-text"><span>ALL Order Stock List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/itemListBOM1') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1)  Order Stock List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/itemListBOM2') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2)  Order Stock List</span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Order Closure List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'orderclosurelist' || $this->router->fetch_method() == 'orderclosurelistBOM1' || $this->router->fetch_method() == 'storepurchaseindentdetails'|| $this->router->fetch_method() == 'orderclosuredetails' || $this->router->fetch_method() == 'orderclosurelistBOM2' ) echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/orderclosurelist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Order Closure List</span></span>                  
                        </a> -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Order Closure List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/orderclosurelist') ?>" class="nav-link">
                                       <span class="nav-text"><span>ALL Order Closure List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/orderclosurelistBOM1') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1)  Order Closure List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/orderclosurelistBOM2') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2)  Order Closure List</span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('D.C. List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'dclist' || $this->router->fetch_method() == 'bomDCDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/dclist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>D.C. List</span></span>                  
                        </a>
                    
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('D.C. List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'surpluspurchaseindentlist' || $this->router->fetch_method() == 'surpluspurchaseindentlistBOM1' || $this->router->fetch_method() == 'surpluspurchaseindentdetailswip' || $this->router->fetch_method() == 'surpluspurchaseindentdetailspiref' || $this->router->fetch_method() == 'surpluspurchaseindentlistBOM2' || $this->router->fetch_method() == 'surplusissuedetails' ) echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/surpluspurchaseindentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>222D.C. List</span></span>                  
                        </a> -->
                         <a href="#" class="dropdown-toggle" data-toggle="dropdown" >S.P.I. List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/surpluspurchaseindentlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>ALL S.P.I.  List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/surpluspurchaseindentlistBOM1') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1)  S.P.I. List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/surpluspurchaseindentlistBOM2') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2)  S.P.I. List</span></span>
                                    </a>
                            </li>
                        </ul>
                    
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Surplus Stock List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'surplusstocklist' || $this->router->fetch_method() == 'surplusstockdetails' || $this->router->fetch_method() == 'surplus_draftdc' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/surplusstocklist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Surplus Stock List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Stock Transfer Memo List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'stockTransferMemoList' || $this->router->fetch_method() == 'stocktransferdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mstoreuser/stockTransferMemoList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Stock Transfer Memo List</span></span>                  
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if($userType == '12') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'requestreceivedlist' || $this->router->fetch_method() == 'BOM1requestreceivedlist' || $this->router->fetch_method() == 'BOM2requestreceivedlist'|| $this->router->fetch_method() == 'financereqreceiveddetails')  echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/requestreceivedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Received List</span></span>                  
                        </a> -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Request Received List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/requestreceivedlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>All Req. Recd. List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/BOM1requestreceivedlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1) Req. Recd. List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/BOM2requestreceivedlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2) Req. Recd. List</span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php }  if(count($ArrRoleInfo)>0 && in_array('Bill Paid List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'billpaidlist' || $this->router->fetch_method() == 'billpaidlistbom1' || $this->router->fetch_method() == 'billpaidlistbom2') echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/billpaidlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Bill Paid List</span></span>                  
                        </a> -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Bill Paid List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/billpaidlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>All Paid List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/billpaidlistbom1') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1) Paid List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/billpaidlistbom2') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2) Paid List</span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php }  if(count($ArrRoleInfo)>0 && in_array('Stock Transfer Memo List',$ArrRoleInfo)) { ?>
                    <li class="<?php if($this->router->fetch_method() == 'stockTransferMemoList' || $this->router->fetch_method() == 'stocktransferdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mfinanceuser/stockTransferMemoList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Stock Transfer Memo List</span></span>                  
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if($userType == '7') { ?> 
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'purchasereceivedlist' || $this->router->fetch_method() == 'departmentapproval' ||  $this->router->fetch_method() == 'purchasereceivedlistbom1' ||  $this->router->fetch_method() == 'purchasereceivedlistbom2' || $this->router->fetch_method() == 'stationery') echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasereceivedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Received List</span></span>                  
                        </a> -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="nav-text fadeable"><span>Request Received List</span></span>  
                        </a>
                        
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasereceivedlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>All Received</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasereceivedlistbom1')?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1) Received</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasereceivedlistbom2')?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2) Received</span></span>
                                    </a>
                                    <a href="<?php echo base_url('MerchantRequestSent/stationery') ?>" class="nav-link">
                                      
                                       <span class="nav-text"><span>Stationery Received</span></span>
                                    </a></span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'bomPurchaseReqQueueList' || $this->router->fetch_method() == 'purchaseQueueDetails' || $this->router->fetch_method() == 'draftpi' || $this->router->fetch_method() == 'bom1PurchaseReqQueueList' || $this->router->fetch_method() == 'bom2PurchaseReqQueueList' ) echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/bomPurchaseReqQueueList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Queue List</span></span>                  
                        </a> -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="nav-text fadeable"><span>Queue List</span></span>  
                        </a>
                        
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/bomPurchaseReqQueueList') ?>" class="nav-link">
                                       <span class="nav-text"><span>All Queue</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/bom1PurchaseReqQueueList')?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1) Queue</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/bom2PurchaseReqQueueList')?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2) Queue</span></span>
                                    </a>
                                    <a href="#" class="nav-link">
                                      
                                       <span class="nav-text"><span>Stationery Queue</span></span>
                                    </a></span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Request Sent List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'purchasesentlist' || $this->router->fetch_method() == 'requestsentdetails'|| $this->router->fetch_method() == 'purchasesentlistbom1' || $this->router->fetch_method() == 'purchasesentlistbom2') echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasesentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Sent List</span></span>                  
                        </a> -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="nav-text fadeable"><span>Request Sent List</span></span>  
                        </a>
                        
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasesentlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>All Request</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasesentlistbom1')?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1) Request</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchasesentlistbom2')?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2) Request</span></span>
                                    </a>
                                    <a href="#" class="nav-link">
                                      
                                       <span class="nav-text"><span>Stationery Request</span></span>
                                    </a></span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('P.I. List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'purchaseindentlist' || $this->router->fetch_method() == 'purchaseindentdetails'  || $this->router->fetch_method() == 'purchaseindentlistbom1' || $this->router->fetch_method() == 'purchaseindentlistbom2'|| $this->router->fetch_method() == 'purchaseindent'|| $this->router->fetch_method() == 'stationerymanagamentpurchaseindent' ||  $this->router->fetch_method() == 'managamentpurchaseindentdetails') echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchaseindentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>P.I. List11111</span></span>                  
                        </a> -->

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="nav-text fadeable"><span>P.I. List</span></span>  
                        </a>
                        
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchaseindentlist') ?>" class="nav-link">
                                       <span class="nav-text"><span>All Purchase Indent</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchaseindentlistbom1') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A1) Purchase Indent</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/purchaseindentlistbom2') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM (A2) Purchase Indent</span></span>
                                    </a>
                                    <a href="<?php echo base_url('management/stationerymanagamentpurchaseindent') ?>" class="nav-link">
                                      
                                       <span class="nav-text"><span>Stationery Purchase Indent</span></span>
                                    </a></span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Bill Paid List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'billpaidlist' || $this->router->fetch_method() == 'billpaiddetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/billpaidlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Bill Paid List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Stock Transfer Memo List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'stockTransferMemoList' || $this->router->fetch_method() == 'stocktransferdetails') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'Mpurchaseuser/stockTransferMemoList') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Stock Transfer Memo List</span></span>                  
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if($userType == '5') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'samplereceivedlist' || $this->router->fetch_method() == 'department' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/samplereceivedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Received List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'samplequeuelist' || $this->router->fetch_method() == 'qa' || $this->router->fetch_method() == 'dclist' || $this->router->fetch_method() ==  'qarequest') echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/samplequeuelist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Queue List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Request Sent List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'samplesentlist' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/samplesentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Sent List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Garment Issued List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'garmentissuedlist' || $this->router->fetch_method() == 'garmentissueddetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/garmentissuedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Garment Issued List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('D.C. List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'sampleDCDetails'  ||  $this->router->fetch_method() == 'midclist' || $this->router->fetch_method() == 'mibomdclist' || $this->router->fetch_method() == 'micaddclist' ||  $this->router->fetch_method() == 'cadDCDetails') echo "active";?> nav-item">
                        <!-- <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/midclist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>D.C. List1111</span></span>                  
                        </a> -->
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="nav-text fadeable"><span>D.C. List</span></span>  
                        </a>
                        
                        
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                 <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/midclist') ?>" class="nav-link">
                                       <span class="nav-text"><span>All D.C. List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/micaddclist') ?>" class="nav-link">
                                       <span class="nav-text"><span>CAD D.C. List</span></span>
                                    </a>
                                    <a href="<?php echo base_url(CNFCOMPANY.'msamplinguser/mibomdclist') ?>" class="nav-link">
                                       <span class="nav-text"><span>BOM D.C.  List</span></span>
                                    </a>    
                                    <a href="#" class="nav-link">
                                       <span class="nav-text"><span>Fabric D.C.  List</span></span>
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if($userType == '4') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'cadreceivedlist' || $this->router->fetch_method() == 'cadDeptDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadreceivedlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Received List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'cadqueuelist' || $this->router->fetch_method() == 'cadDeptQueueDetails' || $this->router->fetch_method() == 'qarequest' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadqueuelist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Queue List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Request Sent List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'cadsentlist' || $this->router->fetch_method() == 'cadDeptSentDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadsentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>Request Sent List</span></span>                  
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('M.I. Received List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'cadindentlist'|| $this->router->fetch_method() == 'cadIndentDetails'|| $this->router->fetch_method() == 'dclist' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/cadindentlist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>M.I. Received List</span></span>
                        </a>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('D.C. List',$ArrRoleInfo)) {?>
                    <li class="<?php if($this->router->fetch_method() == 'caddclist'|| $this->router->fetch_method() == 'cadDCDetails' ) echo "active";?> nav-item">
                        <a href="<?php echo base_url(CNFCOMPANY.'mcaduser/caddclist') ?>" class="nav-link">
                            <span class="nav-text fadeable"><span>D.C. List</span></span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            <?php } else if ($userType == '11') { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <?php if(count($ArrRoleInfo)>0 && in_array('Request Received List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'qareceivedlist'||$this->router->fetch_method() == 'cadqareceivedlist' || $this->router->fetch_method() == 'sampleqareceivedlist'||$this->router->fetch_method() == 'productionqareceiveddetails'||$this->router->fetch_method() == 'qareceiveddetails' || $this->router->fetch_method() == 'productionqareceivedlist') echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Request Received List<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/qareceivedlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>All QA Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/cadqareceivedlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>CAD QA Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/sampleqareceivedlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>Sample QA Request</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/productionqareceivedlist') ?>" class="nav-link">
                                    <span class="nav-text"><span>Production QA Request</span></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'qaqueuelist'||$this->router->fetch_method() == 'cadqaqueuelist'||$this->router->fetch_method() == 'sampleqaqueuelist'||$this->router->fetch_method() == 'productionqaqueuelist'||$this->router->fetch_method() == 'queuelist') echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Queue List<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/qaqueuelist') ?>" class="nav-link">
                                    <span class="nav-text"><span>All QA Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/cadqaqueuelist') ?>" class="nav-link">
                                    <span class="nav-text"><span>CAD QA Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/sampleqaqueuelist') ?>" class="nav-link">
                                    <span class="nav-text"><span>Sample QA Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url(CNFCOMPANY.'mqausers/productionqaqueuelist') ?>" class="nav-link">
                                    <span class="nav-text"><span>Production QA Queue</span></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                     <?php } ?>
                </ul>
            <?php } else { ?>
                <ul class="menu-nav nav navbar-nav pull-right">
                    <li class="<?php if($this->router->fetch_method() == 'orderEnquiryList' || $this->router->fetch_method() == 'addenquiry' || $this->router->fetch_method()=='iorenquirylist' || $this->router->fetch_method()=='isrenquirylist') echo "active";?> nav-item dropdown">
                    <?php if($userType != '2' && (count($ArrRoleInfo)>0 && in_array('Enquiry List',$ArrRoleInfo))) { ?>
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Enquiry List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="allenquiry dropdown-submenu nav-item <?php if($this->router->fetch_method() == 'orderEnquiryList' || $this->router->fetch_method() == 'addenquiry') echo "active";?>">
                                <a tabindex="-1" href="<?= base_url('merchant/orderEnquiryList') ?>">All Enquiry List <span class="carets"></span></a>
                               
                            </li>
                            <li class="allenquiry dropdown-submenu nav-item  <?php if($this->router->fetch_method() == 'iorenquirylist') echo "active";?>">
                              <a href="<?= base_url('merchant/iorenquirylist') ?>">IOR List <span class="carets"></span></a>
                                
                            </li>
                            <li class="allenquiry dropdown-submenu nav-item <?php if($this->router->fetch_method() == 'isrenquirylist') echo "active";?>">
                                <a href="<?= base_url('merchant/isrenquirylist')?>">ISR List <span class="carets"></span></a>
                                
                            </li>
                         </ul>
                     <?php } else if($userType == '2' && (count($ArrRoleInfo)>0 && in_array('Enquiry Authorization List',$ArrRoleInfo))) { ?>
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Enq.Auth. List <span class="caret"></span></a>
                        
                        <ul class="dropdown-menu">
                            <li class="allenquiry dropdown-submenu nav-item <?php if($this->router->fetch_method() == 'orderEnquiryList' || $this->router->fetch_method() == 'addenquiry') echo "active";?>">
                                <a tabindex="-1" href="<?= base_url('management/orderEnquiryList') ?>">All Enquiry List <span class="carets"></span></a>
                                
                            </li>
                            <li class="allenquiry dropdown-submenu nav-item <?php if($this->router->fetch_method() == 'iorenquirylist' || $this->router->fetch_method() == 'iorenquirylist') echo "active";?>">
                                <a href="<?= base_url('management/iorenquirylist') ?>">IOR LIST <span class="carets"></span></a>
                                
                            </li>
                            <li class="allenquiry  dropdown-submenu nav-item <?php if($this->router->fetch_method() == 'isrenquirylist' || $this->router->fetch_method() == 'isrenquirylist') echo "active";?>">
                                <a  href="<?= base_url('management/isrenquirylist') ?>">ISR LIST <span class="carets"></span></a>
                                
                            </li>
                     </ul>
                    <?php } ?>     
                     </li>
                    <?php if(count($ArrRoleInfo)>0 && in_array('WIP List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'manageWip'||$this->router->fetch_method() == 'manageIOR'
                            ||$this->router->fetch_method() == 'manageISR'|| $this->uri->segment(1) == 'wipPrecosting' || ($this->router->fetch_method()=='componentCreation' && $this->uri->segment(4)=='wiplist') ||
                            ($this->uri->segment(1) == 'WorkInProcess'&&($this->router->fetch_method()=='index'||$this->router->fetch_method()=='fabric_program'))
                        ) echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">WIP List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            
                                <?php if($userType != '2') { ?>
                                    <li class="allwiplist dropdown-submenu nav-item">
                                    <a href="<?= base_url('merchant/manageWip') ?>" class="nav-link">
                                        <span class="nav-text"><span>All WIP List</span></span>
                                    </a>
                                    
                                
                                </li>
                                <?php } else if($userType == '2') { ?>
                                    <li class="allwiplist1 dropdown-submenu nav-item">
                                    <a  tabindex="-1" href="<?= base_url('management/manageWip') ?>" class="nav-link">
                                        <span class="nav-text"><span>All WIP List</span></span>
                                    </a>
                                   
                                <li>
                                <?php } ?>
                            
                           
                                <?php if($userType != '2') { ?>
                                    <li class="allwiplist dropdown-submenu nav-item">
                                    <a href="<?= base_url('merchant/manageIOR') ?>" class="nav-link">
                                        <span class="nav-text"><span>IOR List</span></span>
                                    </a>
                                    
                                
                                </a>
                                <?php } else if($userType == '2') { ?>
                                    <li class="allwiplist1 dropdown-submenu nav-item">
                                    <a href="<?= base_url('management/manageIOR') ?>" class="nav-link">
                                        <span class="nav-text"><span>IOR List</span></span></a>
                                        
                                <li>
                                    
                                <?php } ?>
                            
                                <?php if($userType != '2') { ?>
                                    <li class="allwiplist dropdown-submenu nav-item">
                                    <a href="<?= base_url('merchant/manageISR') ?>" class="nav-link">
                                        <span class="nav-text"><span>ISR List</span></span>
                                    </a>
                                    
                                </li>
                                <?php } else if($userType == '2') { ?>
                                    <li class="allwiplist1 dropdown-submenu nav-item">
                                    <a href="<?= base_url('management/manageISR') ?>" class="nav-link">
                                        <span class="nav-text"><span>ISR List</span></span>
                                    </a>
                                    
                                </li>
                                <?php }  ?>
                                    <li class="allwiplist1 dropdown-submenu nav-item">
                                    <a href="<?= base_url('merchant/test') ?>" class="nav-link">
                                        <span class="nav-text"><span>Test List</span></span>
                                    </a>
                                    
                                </li>
                                <?php ?>
                            
                        </ul>
                    </li>
                    <?php } ?>
                    <li class="nav-item dropdown <?php if(($this->router->fetch_method() == 'index'&&$this->uri->segment(1)=='MerchantRequestSent')||$this->router->fetch_method() == 'cad'||
                        $this->router->fetch_method() == 'sample'||$this->router->fetch_method() == 'bom'||$this->router->fetch_method() == 'bom2'||
                        $this->router->fetch_method() == 'embellishment'||$this->router->fetch_method() == 'fabric'||$this->router->fetch_method() == 'production'||
                        $this->router->fetch_method() == 'vessel'||$this->router->fetch_method() == 'stationery'||$this->router->fetch_method() == 'cadrequestlist'||
                        $this->router->fetch_method() == 'samplerequestlist'|| $this->router->fetch_method() == 'bom2requestlist'||  $this->router->fetch_method() == 'bomr2equestlist'|| $this->router->fetch_method() == 'common_list'||$this->router->fetch_method() == 'bomrequestlist'||$this->router->fetch_method() == 'requestlist' || $this->router->fetch_method() == 'managament') echo "active";?>">
                        <?php if($userType != '2' && (count($ArrRoleInfo)>0 && in_array('Request Sent List',$ArrRoleInfo))) { ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Request Sent List <span class="caret"></span></a>
                        <?php } else if($userType == '2' && (count($ArrRoleInfo)>0 && in_array('Work Authorization List',$ArrRoleInfo))) { ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Work Auth. List<span class="caret"></span></a>
                        <?php } ?>
                        <ul class="dropdown-menu">
                            <!-- <li class="nav-item">
                                <a href="<?= base_url('MerchantRequestSent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>All Request</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>All Req. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li> -->

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent') ?>" class="nav-link">
                                        <span class="nav-text"><span>All Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/common_list') ?>" class="nav-link">
                                        <span class="nav-text"><span>All Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/cad') ?>" class="nav-link">
                                        <span class="nav-text"><span>CAD Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/cad') ?>" class="nav-link">
                                        <span class="nav-text"><span>CAD Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                    <?php if($userType != '2') { ?>
                                        <a href="<?= base_url('MerchantRequestSent/sample') ?>" class="nav-link">
                                            <span class="nav-text"><span>Sample Request</span></span>
                                        </a>
                                    <?php } else if($userType == '2') { ?>
                                        <a href="<?= base_url('management/sample') ?>" class="nav-link">
                                            <span class="nav-text"><span>Sample Req. Auth.</span></span>
                                        </a>
                                    <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/bom') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/bom') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/bom2') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/bom2') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/embellishment') ?>" class="nav-link">
                                        <span class="nav-text"><span>Embellishment Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/embellishment') ?>" class="nav-link">
                                        <span class="nav-text"><span>Embellishment Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/fabric') ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/fabric') ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/production') ?>" class="nav-link">
                                        <span class="nav-text"><span>Production Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/production') ?>" class="nav-link">
                                        <span class="nav-text"><span>Production Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/vessel') ?>" class="nav-link">
                                        <span class="nav-text"><span>Vessel Booking Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/vessel') ?>" class="nav-link">
                                        <span class="nav-text"><span>Vessel Booking Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('MerchantRequestSent/stationery') ?>" class="nav-link">
                                        <span class="nav-text"><span>Stationery Request</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('management/stationery') ?>" class="nav-link">
                                        <span class="nav-text"><span>Stationery Req. Auth.</span></span>
                                    </a>
                                <?php } ?>
                            </li>

                        </ul>
                    </li>
                    <?php if(count($ArrRoleInfo)>0 && in_array('Queue List',$ArrRoleInfo)) {?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'merchantallqueue'||$this->router->fetch_method() == 'merchantcadqueue'||
                        $this->router->fetch_method() == 'merchantsamplequeue'||$this->router->fetch_method() == 'merchantbom2queue'||$this->router->fetch_method() == 'managmentallqueue'|| $this->router->fetch_method() == 'managementbom2queue'|| $this->router->fetch_method() == 'merchantembellishmentqueue'||
                        $this->router->fetch_method() == 'merchantfabricqueue'||$this->router->fetch_method() == 'merchantproductionqueue'||$this->router->fetch_method() == 'merchantvesselqueue'||
                        $this->router->fetch_method() == 'merchantstationeryqueue'||$this->uri->segment(3) == 'merchantqueue'||$this->router->fetch_method() == 'managementcadqueue'||
                        $this->router->fetch_method() == 'managementsamplequeue'||$this->router->fetch_method() == 'managementbomqueue'|| $this->router->fetch_method() == 'merchantbomqueue'|| $this->router->fetch_method() == 'managementqueue'|| $this->router->fetch_method() == 'managementfabricqueue') echo "active";?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Queue List <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                 <?php if($userType != '2') { ?>
                                <a href="<?= base_url('company/mqausers/merchantallqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>All Queue</span></span>
                                </a>
                                <?php } else if($userType == '2') { ?>
                                     <a href="<?= base_url('company/mqausers/managmentallqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>All Queue</span></span>
                                </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?= base_url('company/mqausers/merchantcadqueue') ?>" class="nav-link">
                                        <span class="nav-text">
                                            <span>CAD Queue</span>
                                        </span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?= base_url('company/mqausers/managementcadqueue') ?>" class="nav-link">
                                        <span class="nav-text">
                                            <span>CAD Queue</span>
                                        </span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType != '2') { ?>
                                    <a href="<?php echo base_url('company/mqausers/merchantsamplequeue') ?>" class="nav-link">
                                        <span class="nav-text"><span>Sample Queue</span></span>
                                    </a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="<?php echo base_url('company/mqausers/managementsamplequeue') ?>" class="nav-link">
                                        <span class="nav-text"><span>SAMPLE Queue</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType == '2') { ?>
                                    <a href="<?php echo base_url('company/mqausers/managementbomqueue') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Queue</span></span>
                                    </a>
                                    <a href="<?php echo base_url('company/mqausers/managementbom2queue') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Queue</span></span>
                                    </a>
                                <?php } else if($userType == '3' || $userType == '15') { ?> <!-- added planning dept. condition|| $userType == '15' -->
                                    <a href="<?php echo base_url('company/mqausers/merchantbomqueue') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Queue</span></span>
                                    </a>
                                    <a href="<?php echo base_url('company/mqausers/merchantbom2queue') ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Queue</span></span>
                                    </a>
                                <?php } else { ?>
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Queue</span></span>
                                    </a>
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Queue</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('company/mqausers/merchantembellishmentqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>Embellishment Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <?php if($userType == '2') { ?>
                                    <a href="<?php echo base_url('company/mqausers/managementfabricqueue') ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric Queue</span></span>
                                    </a>
                                <?php } else if($userType == '3' || $userType == '15') { ?> <!-- added planning dept. condition|| $userType == '15' -->
                                    <a href="<?php echo base_url('company/mqausers/merchantfabricqueue') ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric Queue</span></span>
                                    </a>
                                <?php } else { ?>
                                    <a href="#" class="nav-link">
                                        <span class="nav-text"><span>Fabric Queue</span></span>
                                    </a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('company/mqausers/merchantproductionqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>Production Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('company/mqausers/merchantvesselqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>Vessel Booking Queue</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('company/mqausers/merchantstationeryqueue') ?>" class="nav-link">
                                    <span class="nav-text"><span>Stationery Queue</span></span>
                                </a>
                            </li>
                        </ul>
                        <b class="sub-arrow"></b>
                    </li>
                    <?php } ?>
                    <?php if(count($ArrRoleInfo)>0 && in_array('M.I. List',$ArrRoleInfo)) { ?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'allmaterialindent'||$this->router->fetch_method() == 'cadmaterialindent'||
                        $this->router->fetch_method() == 'bommaterialindent'||$this->router->fetch_method() == 'bom2materialindent'||$this->router->fetch_method() == 'fabricmaterialindent'||
                        $this->router->fetch_method() == 'stationerymaterialindent' || $this->router->fetch_method() == 'cadIndentDetails' ||  $this->router->fetch_method() == 'mireceiveddetails' ) echo "active";?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">M.I. List <span class="caret"></span></a>
                        <!-- <?php if($userType != '2') { ?> -->
                        <!-- <?php } else if($userType == '2') { ?> -->
                            <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Material Indent Auth. List <span class="caret"></span></a> -->
                        <!-- <?php } ?> -->
                        <ul class="dropdown-menu">
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/allmaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>All Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>All M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/cadmaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>CAD Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>CAD M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/bommaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>BOM  Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>BOM  M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="<?= base_url('merchant/bom2materialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>BOM (A2) Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>BOM (A2) M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/fabricmaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>Fabric Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>Fabric M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('merchant/stationerymaterialindent') ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>Stationery Material Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>Stationery M.I. Auth.</span></span>
                                    <?php } ?>
                                </a>
                            </li>

                        </ul>
                        <b class="sub-arrow"></b>
                    </li>
                    <?php } ?>
                    <?php if($userType != '2' && (count($ArrRoleInfo)>0 && in_array('M.I. List',$ArrRoleInfo))) { ?>
                    <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'allpurchaseindent'||$this->router->fetch_method() == 'yarndeptpilist'||
                        $this->router->fetch_method() == 'fabricpurchaseindent'||$this->router->fetch_method() == 'stationerypurchaseindent' ||$this->router->fetch_method() == 'purchaseindentdetails'||$this->router->fetch_method() == 'merchantpurchaseindentlist' || $this->router->fetch_method() == 'merchantpurchaseindentdetails')  echo "active";?>">
                        <?php if($userType != '2') { ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">P.I. List <span class="caret"></span></a>
                        <?php } else if($userType == '2') { ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">M.I. List <span class="caret"></span></a>
                        <?php } ?>
                        <ul class="dropdown-menu submenu-inner">
                            <li class="nav-item">
                                <a href="<?php echo base_url('merchant/allpurchaseindent'); ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>All Purchase Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>All M.I</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                    <?php if($userType != '2') { ?>
                                        <a href="<?php echo base_url('request/Fabricrequest/yarndeptpilist'); ?>" class="nav-link">
                                            <span class="nav-text"><span>Yarn Purchase Indent</span></span>
                                        </a>
                                    <?php } else if($userType == '2') { ?>
                                        <a href="#" class="nav-link">
                                            <span class="nav-text"><span>CAD M.I</span></span>
                                        </a>
                                    <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType == '7') { ?>
                                    <a href="<?php echo base_url('request/Bomrequest/purchaseindentlist'); ?>" class="nav-link"><span class="nav-text"><span>BOM (A1) Purchase Indent</span></span></a>
                                <?php } else if($userType == '3' || $userType == '15') { ?> <!-- added planning dept. condition|| $userType == '15' -->
                                    <a href="<?php echo base_url('request/Bomrequest/merchantpurchaseindentlist'); ?>" class="nav-link"><span class="nav-text"><span>BOM (A1) Purchase Indent</span></span></a>
                                <?php } else if($userType == '2') { ?>
                                    <a href="#" class="nav-link"><span class="nav-text"><span>BOM (A1) M.I</span></span></a>
                                <?php } else { ?>
                                    <a href="#" class="nav-link"><span class="nav-text"><span>BOM (A1) Purchase Indent</span></span></a>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <?php if($userType == '3' || $userType == '15') { ?> <!-- added planning dept. condition|| $userType == '15' -->
                                    <a href="<?php echo base_url('request/Bom2request/merchantpurchaseindentlist'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Purchase Indent</span></span>
                                    </a>
                                <?php } else if($userType != '2') { ?>
                                    <span class="nav-text"><span>BOM (A2) Purchase Indent</span></span>
                                <?php } else if($userType == '2') { ?>
                                    <span class="nav-text"><span>BOM (A2) M.I</span></span>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('merchant/fabricpurchaseindent'); ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>Fabric Purchase Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>Fabric M.I</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('merchant/stationerypurchaseindent'); ?>" class="nav-link">
                                    <?php if($userType != '2') { ?>
                                        <span class="nav-text"><span>Stationery Purchase Indent</span></span>
                                    <?php } else if($userType == '2') { ?>
                                        <span class="nav-text"><span>Stationery M.I</span></span>
                                    <?php } ?>
                                </a>
                            </li>
                        </ul>
                        <b class="sub-arrow"></b>
                    </li>
                    <?php } ?>
                    <?php if($userType != '2') { ?>
                    <?php if(count($ArrRoleInfo)>0 && in_array('Stationery List',$ArrRoleInfo)) {?>
                        <li class="nav-item <?php if($this->router->fetch_method() == 'stationerylist') echo "active";?>">
                            <a href="<?php echo base_url('merchant/stationerylist'); ?>" class="nav-link">
                                <span class="nav-text fadeable"><span>Stationery List</span></span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(count($ArrRoleInfo)>0 && in_array('Sample Received List',$ArrRoleInfo)) {?>
                        <li class="<?php if($this->router->fetch_method() == 'garmentreceivedlist' || $this->uri->segment(3) == 'garmentreceiveddetails') echo "active";?> nav-item">
                            <a href="<?php echo base_url('merchant/garmentreceivedlist') ?>" class="nav-link">
                                <span class="nav-text fadeable"><span>Garment Received  List</span></span>
                            </a>
                        </li>
                    <?php } ?>    
                    <?php } else if($userType == '2') { ?>
					 <?php if (count($ArrRoleInfo)>0 && in_array('P.I. Approval List',$ArrRoleInfo)) {?>
                        <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'allmgmtpiapproval'||$this->router->fetch_method() == 'yarnmgmtpiapproval'||
                                $this->router->fetch_method() == 'managementpurchaseindentapproval'||$this->router->fetch_method() == 'managementpurchaseindentapprovaldetails'||
                                $this->router->fetch_method() == 'fabricmgmtpiapproval'||$this->router->fetch_method() == 'stationerypiapproval') echo "active";?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">P.I. Appl. List<span class="caret"></span></a>
                            <ul class="dropdown-menu submenu-inner">
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/allmgmtpiapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>All P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Fabricrequest/yarnmgmtpiapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Yarn P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Bomrequest/managementpurchaseindentapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Bom2request/managementpurchaseindentapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/fabricmgmtpiapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric P.I. Appl.</span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/stationerypiapproval'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Stationery P.I. Appl.</span></span>
                                    </a>
                                </li>
                            </ul>
                            <b class="sub-arrow"></b>
                        </li> 
						<?php } ?>
						<?php if (count($ArrRoleInfo)>0 && in_array('P.I. List',$ArrRoleInfo)) {?>
                        <li class="nav-item dropdown <?php if($this->router->fetch_method() == 'allmanagamentpurchaseindent'||$this->router->fetch_method() == 'yarnmanagamentpurchaseindent'||
                                $this->router->fetch_method() == 'managamentpurchaseindent'||$this->router->fetch_method() == 'managamentpurchaseindent'||
                                $this->router->fetch_method() == 'fabricmanagamentpurchaseindent'||$this->router->fetch_method() == 'stationerymanagamentpurchaseindent' ||$this->router->fetch_method() == 'managamentpurchaseindentdetails') echo "active";?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">P.I. List<span class="caret"></span></a>
                            <ul class="dropdown-menu submenu-inner">
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/allmanagamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>All Purchase Indent </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/yarnmanagamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Yarn Purchase Indent </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Bomrequest/managamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A1) Purchase Indent </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('request/Bom2request/managamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>BOM (A2) Purchase Indent </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/fabricmanagamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Fabric Purchase Indent </span></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('management/stationerymanagamentpurchaseindent'); ?>" class="nav-link">
                                        <span class="nav-text"><span>Stationery Purchase Indent  </span></span>
                                    </a>
                                </li>
                            </ul>
                            <b class="sub-arrow"></b>
                        </li>
						<?php } ?>
						
                        <li class="<?php if($this->router->fetch_method() == 'billpaidlist'||$this->uri->segment(3)=="billpaiddetails") echo "active";?> nav-item">
                            <?php if($userType == '2' && (count($ArrRoleInfo)>0 && in_array('Bill Paid List',$ArrRoleInfo))) { ?>
                                <a href="<?= base_url('request/Bomrequest/billpaidlist') ?>" class="nav-link">
                                    <span class="nav-text fadeable"><span>Bill Paid List</span></span>
                                </a>
                            <?php } ?>         
                        </li>

                    <?php } ?>
                    <li class="nav-item">
                        <a href="#" class="">
                            &nbsp;
                        </a>
                    </li>
                </ul>
            <?php } ?>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
