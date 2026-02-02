<?php $this->load->view('template/pageheader');?>
<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">
<div class="wrapper">
  <?php $this->load->view('template/templateheader');?>
    <aside class="main-sidebar"><?php $this->load->view('template/templateleftmenu');?></aside>
    <div class="content-wrapper">
        <section class="content-header">
	  <h1>Change Password</h1>
	  <ol class="breadcrumb"><li><a href="<?php echo base_url()?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li><li>Change Password</li></ol>
	</section>
	<section class="content">
        <div class="row">
            <div class="col-md-12">
				<div class="box box-info">
                    <div class="box-header with-border"><h3 class="box-title">Change Password</h3></div>
                    <form class="form-horizontal" name="frmNameChangePassword" id="frmNameChangePassword">
					  <div class="alert alert-success alert-dismissable hide" id="divSuccessMsgss"></div>
					  <div class="box-body">						
						<div class="form-group">
						  <label for="inputEmail3" class="col-sm-2 control-label">Old Password</label>
						  <div class="col-sm-10">
							<input type="password" name="frmOldPassword" class="form-control" id="frmOldPassword" placeholder="Old Password" value="">
							<div class="herr" id="ErrOldPassword"></div>
						  </div>
						</div>	
						<div class="form-group">
						  <label for="inputEmail3" class="col-sm-2 control-label">New Password</label>
						  <div class="col-sm-10">
							<input type="password" name="frmNewPassword" class="form-control" id="frmNewPassword" placeholder="New Password" value="">
							<div class="herr" id="ErrNewPassword"></div>
						  </div>
						</div>	
						<div class="form-group">
						  <label for="inputEmail3" class="col-sm-2 control-label">Confirm Password</label>
						  <div class="col-sm-10">
							<input type="password" name="frmConfPassword" class="form-control" id="frmConfPassword" placeholder="Confirm Password" value="">
							<div class="herr" id="ErrConfPassword"></div>
						  </div>
						</div>
                      </div>
					  <div class="box-footer">
						<button type="button" class="btn btn-info pull-right" onclick="return fnSaveChangePassword()">Save Changes</button>
                      </div>
					</form>
                </div>
            </div><!--/.col (left) -->
		</div>
	</section>
    </div>
  <?php $this->load->view('template/templatefooter');?>
  <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url();?>assets/js/profile.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<?php $this->load->view('template/pagefooter');?>