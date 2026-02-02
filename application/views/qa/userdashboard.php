<?php $this->load->view(CNFCOMPANY.'template/pageheader'); $ArrLoggedUserInfo = fnGetUserLoggedInfo(1); $VarUserType = $ArrLoggedUserInfo['usertype']; $ArrUserDetails = fnGetUserLoggedInfo(); ?>
<body class="layout-top-nav">
<div class="wrapper">
  <?php $this->load->view(CNFCOMPANY.'template/templateheader');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1>Q. A. Dept. Dashboard<small>Control panel</small></h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 pdl0">
				<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 pdl0">

				</div>
			</div>
		</div>
	</section>
  </div><!-- /.content-wrapper -->
  <?php $this->load->view(CNFCOMPANY.'template/templatefooter');?>
  <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
<?php $this->load->view(CNFCOMPANY.'template/pagefooter');?>