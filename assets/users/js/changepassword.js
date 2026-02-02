function fnSaveChangePassword() {
	$('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').html('');
	var InpOldPassword		= $("#frmOldPassword").val();
	var InpPassword			= $("#frmNewPassword").val();
	var InpConfPassword		= $("#frmConfPassword").val();
	if(jsTrim(InpOldPassword)== ""){
		$('#ErrOldPassword').html("Please fill the old Password");
		$('#frmOldPassword').focus();
		$('#frmOldPassword').css("border", "1px solid #B94A48");
		return false;		
	} else if(jsTrim(InpOldPassword).length<=5) {
		$('#ErrOldPassword').html("Invalid Password!");
		$('#frmOldPassword').focus();
		$('#frmOldPassword').css("border", "1px solid #B94A48");
		return false;
	}
	if(jsTrim(InpPassword)== ""){
		$('#ErrNewPassword').html("Please fill the New Password");
		$('#frmNewPassword').focus();
		$('#frmNewPassword').css("border", "1px solid #B94A48");
		return false;		
	} else if(jsTrim(InpPassword).length<=5) {
		$('#ErrNewPassword').html("Password Should be a minimum 6 character");
		$('#frmNewPassword').focus();
		$('#frmNewPassword').css("border", "1px solid #B94A48");
		return false;	
	}
	if(jsTrim(InpConfPassword)== ""){
		$('#ErrConfPassword').html("Please fill the confirm Password");
		$('#frmConfPassword').focus();
		$('#frmConfPassword').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(InpPassword)!=jsTrim(InpConfPassword)){
		$('#ErrNewPassword').html("Password and Confirm Password should be same");
		$('#frmConfPassword').focus();
		$('#frmConfPassword').css("border", "1px solid #B94A48");
		return false;		
	}
	var Parameters = "op="+InpOldPassword+"&np="+InpPassword+"&cp="+InpConfPassword;
	MakePostRequest(base_path+'seller/profile/updatePassword',Parameters,'json',fnChangePasswordRes);
	return false;
}

function fnChangePasswordRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			$('#ErrOldPassword').html(data.msg);
			return false;
		} else if(data.errcode==1){ 			
			resetForm('frmNameChangePassword');
			$('#divSuccessMsgss').html(data.msg);
			$('#divSuccessMsgss').removeClass('hide');
		}
	}
}