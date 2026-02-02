<?php $this->load->view(CNFCADMIN.'template/pageheader'); ?>
<body class="hold-transition login-page">
<style>
.field_icon{
position: relative;
margin: -24px 10px -0px 10px;
float: right;
cursor:pointer;
}
</style>
<div class="login-box"> 
  <div class="login-logo">
		  <a href="javascript:void(0);" style="color:navy;font-size:22px"><?php echo COMPANYNAME?></a>
	 <!-- /.login-logo -->
  </div><!-- /.login-logo -->
  <div class="login-box-body">
	<div id="divLoginBox">
		<p class="login-box-msg"><b>LOGIN</b></p>
		<form method="post" action="#" name="frmNameLogin" id="frmNameLogin">
		  <div class="herr" id="ErrLoginMsg"></div>
		  <div class="form-group has-feedback">
			<input type="email" name="frmEmail" id="frmEmail" class="form-control" placeholder="E-mail">
			<!--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>-->
			<div id="ErrEmail" class="herr"></div>
		  </div>
		  <div class="form-group has-feedback">
			<input type="password" name="frmPass" id="frmPass" class="form-control" placeholder="Password">
			<span for="icon" class="field_icon">
            <i class="fa fa-eye toggle-password" aria-hidden="true" onclick="togglepwd('frmPass','toggle-password')"></i>
            </span>
			<!--<span class="glyphicon glyphicon-lock form-control-feedback"></span>-->
              <div class="herr" id="ErrfrmPass"></div>
		  </div>
		  <!--<div class="">
				<input type="checkbox" name="frmLoginRememberMe" id="frmLoginRememberMe">&nbsp;<label for="frmLoginRememberMe">Remember Me</label>
		  </div>-->
		  <div class="row">
			<div class="col-xs-8">
			  
			</div><!-- /.col -->
			<div class="col-xs-4">
                <div id="demo"></div>
			  <button type="button" class="btn btn-primary btn-block btn-flat" id="frmSignInBtn" onclick="return fnLogin();">Sign In</button>
			</div><!-- /.col -->
		  </div>
		</form>
		<a href="javascript:void(0);" onclick="fnForgetPwdProStart();">I forgot my password</a><br>
	</div>
	<div id="divFPBox" class="hide">
		<p class="login-box-msg"><b>FORGOT PASSWORD</b></p>
		<form method="post" action="#" name="frmNameForgotPass" id="frmNameForgotPass">
		  <div class="successmsg" id="ErrForgotPassMsg"></div>
		  <div class="form-group has-feedback">
			<input type="email" name="frmFPEmail" id="frmFPEmail" class="form-control" placeholder="E-mail">
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			<div id="ErrFPEmail" class="herr"></div>
		  </div>
		  <div class="row">
			<div class="col-xs-4">

			  <button type="submit" class="btn btn-primary btn-block btn-flat" onclick="return fnForgotPass();">Submit</button>
			</div><!-- /.col -->
		  </div>
		</form>
		<!--<a href="javascript:void(0);" onclick="fnShowLoginBox(1);">Back to Login</a><br>-->
	</div>
  </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
<?php $this->load->view(CNFCADMIN.'template/pagefooter');?>
<script src="<?php echo base_url();?>assets/js/ajax.js"></script>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js"></script>
<script src="<?php echo base_url();?>assets/js/login.js"></script>
<script type="text/javascript">
//for showing password on click of eye icon
function togglepwd(id,clsname){
$('.'+clsname).toggleClass("fa-eye-slash");
var input = $("#"+id);
input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password');
}
</script>