<?php
    $ArrLoggedUserInfo        = fnGetUserLoggedInfo(1);
    //print_r($ArrLoggedUserInfo);
    if(empty($ArrLoggedUserInfo)) {
        //redirect(base_url());
    }
    else {
        $VarUserType            = $ArrLoggedUserInfo['usertype'];
        $ArrUserDetails         = fnGetUserLoggedInfo();
    }
    if(isset($ArrProfileInfo['usertype'])) {
        $ArrUt = unserialize(ARRUSERTYPE);
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo COMPANYNAME?></title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <link rel="shortcut icon" href="<?php echo base_url()."assets/web/";?>images/favicon.ico">
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/fontawesome/css/font-awesome.min.css?rn=<?php echo CNFJSCSSRANDNO ?>">
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/AdminLTE.min.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/skins/_all-skins.min.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/custom.css">
      <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
    <![endif]-->
    <script>
        var base_path = '<?php echo base_url(); ?>';
        var GlbCAdminFdr = '<?php echo CNFCADMIN; ?>';
        var GlbCompanyFdr = '<?php echo CNFCOMPANY; ?>';
        var GlbBAdminFdr = '<?php echo CNFBADMIN; ?>';
    </script>
      <style>
          .dataTables_wrapper .dataTables_length select {
              height: 30px !important;
              padding: 5px 7px !important;
          }
          table.dataTable {
              margin: 15px auto !important;
          }
          .add_btn{
              padding: 5px 7px;margin-left: 35px;margin-top: 0;margin-bottom: 4px;
          }
          .input-small > input{
              width: 10px !important;
          }

          .navbar-nav > li > a {
              font-size: 14px !important;
              font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
          }
          table.dataTable thead th, table.dataTable thead td {
              border-bottom: 0 solid #111 !important;
          }
          .box {
              border-top: 0;
              box-shadow: unset !important;
          }
          .header-title{
              margin-top: 2px;
              margin-bottom: 8px;
              margin-left: 3px;
          }

          /*.dropdown-menu {
              background-color: #ebecec;
          }*/
          .dropdown-menu > li > a {
              color: #022B61;
              background-color: #EBECEC;
              border: 1px solid #fff;
              font-size: 12px !important;
          }
          table.dataTable thead th, table.dataTable thead td {
              padding: 14px 8px !important;
              font-size: 13px;
          }
          table.dataTable thead th, table.dataTable tfoot th {
              font-weight: 500 !important;
          }
          table.dataTable tbody th, table.dataTable tbody td {
              padding: 12px 8px !important;
          }
          .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
              border: 0 solid #f4f4f4 !important;
          }
          .table-bordered {
              border: 0 solid #f4f4f4 !important;
          }
      </style>
      <!-- Left side column. contains the logo and sidebar -->

  </head>
