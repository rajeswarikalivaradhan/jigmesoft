function fnShowLoginForgotPasswd(VarDivShow) {
	var ArrProfileContList = ["divForgotPassInfo","divLoginInfo"];
	//Remove Class
	for(i=0;i<ArrProfileContList.length;i++) {
		$("#"+ArrProfileContList[i]).removeClass('show');
		$("#"+ArrProfileContList[i]).removeClass('hide');
	}
	//Add Class
	for(i=0;i<ArrProfileContList.length;i++) {
		if(VarDivShow!=ArrProfileContList[i]) {
			$("#"+ArrProfileContList[i]).addClass('hide');
		}
	}
	return false;
}

function fnValidateSellerLogin() {
	$('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').html('');
	var ProfileFormData							= false;
	var LoginEmail								= $("#frmLoginEmail").val();
	var LoginPassword							= $("#frmLoginPassword").val();

	if(jsTrim(LoginEmail)== ""){
		$('#ErrLoginEmail').html("Please fill the Business Name");
		$('#frmLoginEmail').focus();
		$('#frmLoginEmail').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(LoginPassword)== ""){
		$('#ErrLoginPassword').html("Please choose the Entity Status");
		$('#frmLoginPassword').focus();
		$('#frmLoginPassword').css("border", "1px solid #B94A48");
		return false;		
	}

	if (window.FormData){
		ProfileFormData								= new FormData();
		ProfileFormData.append("e",LoginEmail);
		ProfileFormData.append("p",LoginPassword);
	}
	$.ajax({
		url 		: base_path+'seller/login/userLogin',
		data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
		cache       : false,
		contentType : false,
		processData : false,
		type        : 'POST',
		success     : function(data, textStatus, jqXHR){
			data = jQuery.parseJSON(data);
			fnValidateSellerLoginRes(data);
		}
	});
	return false;
}

function fnValidateSellerLoginRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			$('#ErrLoginEmail').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			window.location.href=base_path+"seller/dashboard/";
		}
	}
}

function fnForgotPass() {
	$('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').html('');
	var InpEmail		= $("#frmForgotEmail").val();
	if(jsTrim(InpEmail)== ""){
		$('#ErrForgotEmail').html("Please fill the E-Mail Id");
		$('#frmForgotEmail').focus();
		$('#frmForgotEmail').css("border", "1px solid #B94A48");
		return false;		
	}
	var Parameters = "e="+InpEmail;
	MakePostRequest(base_path+'seller/login/forgotpassword',Parameters,'json',fnForgotPassRes);
	return false;
}

function fnForgotPassRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			$('#ErrForgotEmail').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			resetForm('frmNameForgotPass');
			$('#divSuccessForgotPassInfoMsg').html(data.msg);
		}
	}
}