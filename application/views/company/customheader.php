<head lang="en" style="font-size: 0.9em">
<?php $ArrProfileInfo = fnGetUserLoggedInfo(1);
$ArrCompanyRes = $this->companymodel->fnGetCompanyInfo($ArrProfileInfo['companyid']);
$subscriber_detail =$this->companymodel->subscriber_detail($ArrProfileInfo['subscriber_id']);

$VarDesignation = '';
//if(!empty($ArrProfileInfo['desgnid'])) {
    // $ObjDesignation = $this->companymodel->headerUserDesignation($ArrProfileInfo['desgnid']);
    // $VarDesignation = @$ObjDesignation->desgn;
//}
if(!empty($ArrProfileInfo['id'])) {
    $ObjDesignation = $this->companymodel->headerUserDesignationNew($ArrProfileInfo['id']);
    $VarDesignation = @$ObjDesignation->designation;
}
?>



    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <title><?php echo COMPANYNAME ?></title>
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/bootstrap/dist/css/bootstrap.css">

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600&display=swap">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/@fortawesome/fontawesome-free/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/@fortawesome/fontawesome-free/css/regular.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/@fortawesome/fontawesome-free/css/brands.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/node_modules/@fortawesome/fontawesome-free/css/solid.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/dist/css/ace-font.css">
    <!-- ace.css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/dist/css/ace.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/ace/dist/css/ace-themes.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/uploadfile-order.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <style>
        .f-16{
            font-size: 16px;
        }
        .f-14{
            font-size: 14px;
        }
        .f-13{
            font-size: 13px;
        }
        .f-12{
            font-size: 12px;
        }
        .text-royal-blue {
            font-weight: 600;
             /*color: #022B61 !important; */
             color: #0036ae !important; 
            /*color: #000000 !important;*/
        }
        .swal2-title {
            font-size: 20px !important;
        }
        .nav-tabs .btn.active {
            font-weight: 400;
        }
        /*@media (min-width: 1200px) {
            .navbar-white .navbar-inner {
                border-bottom-color: #e6eaed !important;
            }
        }*/
        @media (max-width: 999px) {
            .d-xl-none_custom {
                display: block !important;
            }
        }
        @media (min-width: 1000px) {
            .d-xl-none_custom {
                display: none !important;
            }
        }

        #infobox-row {
            box-shadow: 0 0 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.25rem;
            overflow: hidden;
        }

        @media (max-width: 991.98px) {
            #infobox-row {
                box-shadow: none;
                border-radius: 0;
            }

            #infobox-row div[role=button] {
                border-width: 0 !important;
                border-radius: 0.25rem;
                box-shadow: 0 0 0.25rem rgba(0, 0, 0, 0.075);
            }
        }
        .user-image {
    width: 35px;
    margin-top: 0px;
    margin-left: 10px;
    height: 35px;
    border-radius:50%;
        }
        .user-body:hover{
    /*background-color: #dbebf8!important;*/
    background-color: #ebeff1!important;
    }
    .profile_how:hover{
        background-color: #022B61 !important;
    }
    </style>
    <!-- overlayScrollbars -->
    <script src="<?php echo base_url(); ?>assets/js/new/jquery.min.js"></script>
    <script>
        var base_path     = '<?php echo base_url();?>';
        var GlbCAdminFdr  = '<?php echo CNFCADMIN;?>';
        var GlbCompanyFdr = '<?php echo CNFCOMPANY;?>';
    </script>
<style>
    .bg-lg{background-color: #b0efb0 !important; }
    .bg-red{background-color: #ef5454 !important; }
    .hide{display:none !important}
    .no-border{border:0 !important}
    .no-padding{padding:0 !important}
    .no-margin{margin:0 !important}
    .no-shadow{box-shadow:none!important}
    .nav{
        margin-left:auto !important;
        font-size: 14px !important;
        font-family: "Helvetica Neue",Helvetica,Arial;
    }
    .navbar-blue-royal {
        background-color: #022B61;
    }
    .sidebar-white .nav > .nav-item > .nav-link:hover > .nav-icon {
        color: #498ac4;
    }
    .sidebar.sidebar-h .nav-fill .nav-item {
        font-size: small;
    }
    .sidebar .submenu .nav-link {
        min-height: 1.6rem !important;
        background-color: #ebecec;
        color: #022B61;
    }
    .sidebar .submenu .nav-link:hover {
        background-color: #c2c0c0;
        color: #011532;
        list-style-type: none;
        list-style: unset !important;
        list-style-position: outside;
    }
    .sidebar.sidebar-h {
        height: 33% !important;
    }
    .sidebar .nav > .nav-item > .nav-link {
        color: #022B61 !important;
        min-height: 35px;
        background-color: #ebecec;
        font-family: "Helvetica Neue",Helvetica,Arial;
        font-size: 13px !important;
        font-weight: 400 !important;
    }
    .sidebar.sidebar-h .nav > .nav-item > .submenu {
        min-width: 15em !important;
    }
    .navbar-nav .dropdown-menu {
    position: absolute!important;
    /* float: none; */
    /* commented due to alignment right: -50px;*/
    right:-28px;
    top:100px; /* newly added for alignment */
    left: auto;
    border-radius:3px!important; /* newly added for alignment */
    
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
.user-menu > .dropdown-menu {
    padding:0px!important;
}
 .dropdown-menu > .user-body {
    padding: 15px;
    border-bottom: 1px solid #f4f4f4;
    border-top: 1px solid #dddddd;
    border-bottom-right-radius: 4px;
    border-bottom-left-radius: 4px;
    width: 280px!important;
}
.dropdown-menu > .user-footer {
    background-color: #f9f9f9;
    padding: 10px;
    height:50px;
}
  .dropdown-menu > .user-body a {
    text-decoration:none!important;
    color:#444 !important;
    font-size:14px!important;
}
.dropdown-menu {
    border-top-right-radius: 0;
    border-top-left-radius: 0;
    padding:0px;
    border-top-width: 0;
    padding:5px 0px!important;
    
   
}
.navbar-nav > li > .dropdown-menu {
    margin-top: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
 
.navbar-custom-menu {
    float: right;
}
.pull-left{
    float:left!important;
}
.pull-right{
    float:right!important;
}
.btn-default {
    padding: 5px 10px!important;
    font-size: 12px!important;
}
/*@media (min-width: 768px){*/
/*   .navbar-nav > li  {*/
/*    padding-top: 15px;*/
/*    padding-bottom: 15px;*/
/*} */
/*}*/
.nav > li > a {
    position: relative; 
    display: block;
    padding: 10px 15px;
}
</style>

</head>
<body>
<div class="body-container">
<nav class="navbar navbar-sm navbar-fixed-xl navbar-expand-lg navbar-blue-royal">

    <div class="navbar-inner">
        <!--<div class="container container-plus">-->
        <div class="navbar-intro justify-content-xl-between">
            <button type="button" class="btn btn-burger burger-arrowed static collapsed ml-2 d-flex d-xl-none_custom" data-toggle-mobile="sidebar" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
                <span class="bars"></span>
            </button><!-- mobile sidebar toggler button -->
            <div class="navbar-content flex-grow-0 ml-4 text-white nav_items">
                 |<span class="px-2 text-center">AZIBO INFOTECH PRIVATE LIMITED</span>|
            </div>
            <!--<a class="navbar-brand text-white" href="#">-->
            <!--    <img src="<?php echo base_url(); ?>assets/users/img/logo.png" alt="AdminLTE Logo" class="hide brand-image img-circle elevation-3" style="width: 25%;">-->
            <!--    <span style="position: relative;border: 2px solid white;border-radius: 34px;padding: 1px 21px;background: #022B61;top: 30px;">JIGME</span>-->
            <!--</a><!-- /.navbar-brand -->-->

            <button type="button" class="btn btn-burger mr-2 d-none d-xl-flex hide" data-toggle="sidebar" data-target="#sidebar" aria-controls="sidebar" aria-expanded="true" aria-label="Toggle sidebar">
                <span class="bars"></span>
            </button>
        </div><!-- /.navbar-intro -->
        <!--<div class="navbar-content text-whitev nav_items">-->
        <!--    |<span class="px-2 text-center"><?= @$ArrCompanyRes[0]['companyname'] ?></span>|-->
        <!--</div>-->
        <!-- .navbar-content -->
        <!-- mobile #navbarMenu toggler button -->
        <button class="navbar-toggler ml-1 mr-2 px-1" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navbar menu">
            <span class="pos-rel">
                <img class="border-2 brc-white-tp1 radius-round" width="36"
                     src="<?php echo base_url()?>assets/img/avatar6.jpg" alt="Jason's Photo">
                  <span class="bgc-warning radius-round border-2 brc-white p-1 position-tr mr-n1px mt-n1px"></span>
            </span>
        </button>
        <div class="navbar-content flex-grow-0 ml-auto text-white nav_items">
            <div class=" mx-lg-2">
                 |<span class="pr-4 pl-4 text-center">
                           <?php echo $subscriber_detail->companyname; ?>
                       </span>
                |<span class="px-2">
                        <?php
                        if (isset($ArrProfileInfo['usertype'])) {
                            $ArrUt = unserialize(ARRUSERTYPE);
                            echo $ArrUt[$ArrProfileInfo['usertype']].(!empty($ArrProfileInfo['dept_usercount'])?' - '.$ArrProfileInfo['dept_usercount']:'');
                        }
                        ?>
                  </span>|<span class="px-2"><?= @$ArrProfileInfo['name']; ?></span>|
                  
            </div>
        </div>
        <div class="navbar-menu collapse navbar-collapse navbar-backdrop pl-0" id="navbarMenu">

            <div class="navbar-nav pl-0 pr-5">
                <ul class="nav pl-0">
                    <li class="nav-item dropdown dropdown-mega flex-grow-0 ml-auto pl-0 text-white">
                        <a class="pl-0 nav-link nav_items dropdown-toggle text-white" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                           
                            <span class="pl-0 pr-2 "><?= $VarDesignation?></span>|
                            
                        </a>
                       
                    </li>

                    <li class="pl-0 nav-item dropdown order-first order-lg-last">
                        <a class="pl-0 nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <?php
                        if (@$ArrProfileInfo['pimg'] <> '') { ?>
                            <img src="<?php echo base_url(); ?>uploads/employee/profile/<?php echo @$ArrProfileInfo['pimg'] ?>"
                                 class="user-image" alt="User Image">
                        <?php }
                        else { ?>
                            <img src="<?php echo base_url(); ?>assets/img/avatar5.png" class="user-image" style="margin-right: -35px; float: none !important;"
                                 alt="User Image">
                        <?php } ?>
                        </a>

                       <ul class="user-menu dropdown-menu navbar-custom-menu" style="padding:0px!important">
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
                            <div class="text-center"><a href="<?php echo base_url('profile') ?>">User
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

                    <!-- /.nav-item:last -->

                </ul><!-- /.navbar-nav menu -->
            </div><!-- /.navbar-nav -->

        </div><!-- /#navbarMenu -->
        <!--</div>-->
    </div><!-- /.navbar-inner -->

</nav>
    <?php 
   // $this->load->view(CNFCOMPANY . 'template/left_menu');
    $this->load->view(CNFCOMPANY . 'template/custom_left_menu');
    ?>
<div class="main-container bgc-white">
    <div role="main" class="main-content">
        <div class="page-content p-1">


