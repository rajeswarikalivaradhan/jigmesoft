<?php $this->load->view(CNFCOMPANY.'template/pageheader'); $ArrLoggedUserInfo = fnGetUserLoggedInfo(1); $VarUserType = $ArrLoggedUserInfo['usertype']; $ArrUserDetails = fnGetUserLoggedInfo(); ?>
<body class="layout-top-nav">
<div class="wrapper">
  <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
  <!-- Left side column. contains the logo and sidebar -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1>Management Dashboard<small>Control panel</small></h1>
	  <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Management Dashboard</li>
	  </ol>
	</section>
	<section class="content">
		<div class="row">
		</div>
	</section>
  </div><!-- /.content-wrapper -->
  <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
  <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>