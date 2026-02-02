function fnSaveSellerSignup() {
	$('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').html('');
	var ProfileFormData							= false;
	var BusinessName							= $("#frmBusinessName").val();
	var EntityType								= $("#frmBusinessStatus").val();
	var ContactName								= $("#frmContactName").val();	
	var ContactEmail							= $("#frmContactEmail").val();
	var ContactMobile							= $("#frmContactMobile").val();

	if(jsTrim(BusinessName)== ""){
		$('#ErrBusinessName').html("Please fill the Business Name");
		$('#frmBusinessName').focus();
		$('#frmBusinesssName').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(EntityType)== ""){
		$('#ErrBusinessStatus').html("Please choose the Entity Status");
		$('#frmBusinessStatus').focus();
		$('#frmBusinessStatus').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(ContactName)== ""){
		$('#ErrContactName').html("Please fill the Contact Name");
		$('#frmContactName').focus();
		$('#frmContactName').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(ContactEmail)== ""){
		$('#ErrContactEmail').html("Please fill the E-Mail Id");
		$('#frmContactEmail').focus();
		$('#frmContactEmail').css("border", "1px solid #B94A48");
		return false;		
	} else if(!isEmail(ContactEmail)) {
		$('#ErrContactEmail').html("Please fill the Vaild E-Mail Id!!!");
		$('#frmContactEmail').focus();
		$('#frmContactEmail').css("border", "1px solid #B94A48");
		return false;		
	}	
	if(jsTrim(ContactMobile)== ""){
		$('#ErrContactMobile').html("Please fill the Mobile Number");
		$('#frmContactMobile').focus();
		$('#frmContactMobile').css("border", "1px solid #B94A48");
		return false;		
	}

	if (window.FormData){
		ProfileFormData								= new FormData();
		ProfileFormData.append("bn",BusinessName);
		ProfileFormData.append("et",EntityType);
		ProfileFormData.append("cm",ContactMobile);	
		ProfileFormData.append("ce",ContactEmail);	
		ProfileFormData.append("cn",ContactName);
	}
	$.ajax({
		url 		: base_path+'seller/register/userSignup',
		data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
		cache       : false,
		contentType : false,
		processData : false,
		type        : 'POST',
		success     : function(data, textStatus, jqXHR){
			data = jQuery.parseJSON(data);
			fnSaveSellerSignupRes(data);
		}
	});
	return false;
}

function fnSaveSellerSignupRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			$('#ErrContactEmail').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			$("#divRegisterInfo").hide();
			$("#divSuccessBasicInfoMsg").removeClass('hide');
			$("#divSuccessBasicInfoMsg").html("Thanks for signing up!.<br><br>Hi "+$("#frmContactName").val()+", <br> We have sent a verification link to your e-mail address "+$("#frmContactEmail").val()+". Please click on the verification link to help us create your account.");
			$("#divRegisterNote").removeClass('hide');			
			resetForm("frmUserSignup");
		}
	}
}