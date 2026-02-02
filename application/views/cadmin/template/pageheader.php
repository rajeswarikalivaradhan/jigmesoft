<?php
$ArrLoggedUserInfo        = fnGetUserLoggedInfo(1);
if(empty($ArrLoggedUserInfo)) {
  //redirect(base_url());
} else {
  $VarUserType            = $ArrLoggedUserInfo['usertype'];
  $VarProfilePermission   = $ArrLoggedUserInfo['pp'];
  $ArrUserDetails         = fnGetUserLoggedInfo();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo COMPANYNAME?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <!-- Font Awesome -->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css?rn=<?php /*echo CNFJSCSSRANDNO*/?>">-->
      <link rel="stylesheet" href="<?php echo base_url();?>assets/fontawesome/css/font-awesome.min.css?rn=<?php echo CNFJSCSSRANDNO ?>">
    <!-- Ionicons -->
    <!--<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css?rn=<?php echo CNFJSCSSRANDNO?>">-->
	<!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/AdminLTE.min.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/skins/_all-skins.min.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/flat/blue.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css?rn=<?php echo CNFJSCSSRANDNO?>">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css?rn=<?php echo CNFJSCSSRANDNO?>">

	<!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.1.4.min.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?php echo base_url();?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
    <![endif]-->
	<script>
      var base_path     = '<?php echo base_url();?>';
      var GlbCAdminFdr  = '<?php echo CNFCADMIN;?>';
      var GlbCompanyFdr = '<?php echo CNFCOMPANY;?>';
    </script>
    <style type="text/css">
      .filterbyalpha {
        padding:10px;
        width: 100%;
      }
      .alpha_all_filter {
        cursor: pointer;
        border:1px solid #000;
        padding:5px 0px 5px 0px;
        float:left;
        width:4%;
        text-align: center;
      }
      .alpha_filter {
        cursor: pointer;
        border-right:1px solid #000;
        border-top:1px solid #000;
        border-bottom:1px solid #000;
        padding:5px 0px 5px 0px;
        float:left;
        width:3.69%;
        text-align: center;
      }
      .pd10 {
        padding:10px;
      }
      .wd80{
        width:97%;
      }
      .mpull-left{
        float:left !important;
      }
      .pdt10 {padding-top:10px;}
      .pdt5 {padding-top:5px;}
      .green { color: green; }
      .red { color: red; }
      .sortable{}
    </style>
  </head>