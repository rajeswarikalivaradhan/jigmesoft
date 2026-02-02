
$(function() {
/*	if (localStorage.chkbx && localStorage.chkbx != '') {
		$('#frmLoginRememberMe').attr('checked', 'checked');
		$('#frmEmail').val(localStorage.usrname);
		$('#frmPass').val(localStorage.pass);
	} else {
		$('#frmLoginRememberMe').removeAttr('checked');
		$('#frmEmail').val('');
		$('#frmPass').val('');
	}*/
});

var pw = document.getElementById("frmPass");
var email = document.getElementById("frmEmail");
email.addEventListener("keyup",function (e) {
    if(e.keyCode === 13) document.getElementById("frmSignInBtn").click();
});

pw.addEventListener("keyup",function (e) {
    if(e.keyCode === 13) document.getElementById("frmSignInBtn").click();
});

function fnLogin() {
    $('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').text('');
	var InpEmail		= $("#frmEmail").val();
	var InpPassword		= $("#frmPass").val();
	if(jsTrim(InpEmail)== ""){
		$('#ErrEmail').text("Please fill the E-Mail Id");
		$('#frmEmail').focus();
		$('#frmEmail').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(InpPassword)== ""){
        $('#ErrfrmPass').text("Please fill the Password");
		$('#frmPass').focus();
		$('#frmPass').css("border", "1px solid #B94A48");
		return false;		
	}
	fnRemeberMePass();
	var Parameters = "e="+encodeURIComponent(InpEmail)+"&p="+encodeURIComponent(InpPassword);
	//MakePostRequest(base_path+'login/validate',Parameters,'json',FnDisplayLogin);
	MakeAsynPostRequest(base_path+'login/validate',Parameters,'json',FnDisplayLogin);
}

function FnDisplayLogin(data){
	console.log(data,'data after log');
	if(data!='') {
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode=='-1') {
			$('#ErrLoginMsg').text(data.msg);
			return false;
		} else if(data.errcode=='1') {
			if(data.ut==0) {
			//commented for showing business admin	window.location.href=base_path+'cadmin/dashboard';
			//window.location.href=base_path+'badmin/dashboard';
			window.location.href=base_path+'badmin/msubscription/manage';
			} else if(data.ut==1) {
				//window.location.href=base_path+'dashboard';
				window.location.href=base_path+GlbCompanyFdr+'muser/manage';
			} else if(data.ut==2) {
                window.location.replace(base_path+'management/manageWip');
			}
			else if(data.ut==3 || data.ut==15) {
                //window.location.href=base_path+GlbCompanyFdr+'mmerchantuser';
				window.location.href=base_path+'merchant/manageWip';
			}
            else if(data.ut==4) {
				//window.location.href=base_path+GlbCompanyFdr+'mcaduser';
                window.location.href=base_path+GlbCompanyFdr+'mcaduser/cadqueuelist';
            }
            else if(data.ut==5) {
                //window.location.href=base_path+GlbCompanyFdr+'msamplinguser';
				window.location.href=base_path+GlbCompanyFdr+'msamplinguser/samplequeuelist';
            }
            else if(data.ut==6) {
                //window.location.href = base_path+GlbCompanyFdr+'mfabricuser';
				window.location.href = base_path+GlbCompanyFdr+'mqausers/merchantallqueue';
            }
            else if(data.ut==7) {
                window.location.href = base_path+GlbCompanyFdr+'mpurchaseuser';
            }
            else if(data.ut==8) {
                //window.location.href = base_path+GlbCompanyFdr+'mstoreuser';
				window.location.href = base_path+GlbCompanyFdr+'Mstoreuser/purchaseindentlist';
            }
            else if(data.ut==9) {
                window.location.href = base_path+GlbCompanyFdr+'mproductionuser';
            }
            else if(data.ut==10) {
                window.location.href = base_path+GlbCompanyFdr+'mlabuser';
            }
            else if(data.ut==11) {
                window.location.href = base_path+GlbCompanyFdr+'mqausers';
            }
            else if(data.ut==12) {
                window.location.href = base_path+GlbCompanyFdr+'mfinanceuser';
            }
            else if(data.ut==13) {
                window.location.href = base_path+'docandlocuser';
            }else if(data.ut==16) {
			//window.location.href=base_path+'badmin/dashboard';
			window.location.href=base_path+'badmin/msubscriber/manage';
			}  
            else {
                window.location.href = base_path+'/Dashboard';
            }
		}
	}
}

function fnRemeberMePass() {
	if($('#frmLoginRememberMe').is(':checked')) {
		//localStorage.usrname = $('#frmEmail').val();
		//localStorage.pass = $('#frmPass').val();
		//localStorage.chkbx = $('#frmLoginRememberMe').val();
	} else {
		//localStorage.usrname = '';
		//localStorage.pass = '';
		//localStorage.chkbx = '';
	}
}

function fnForgetPwdProStart() {
	$("#divFPBox").removeClass('hide')
}

function fnForgotPass() {
	$('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').text('');
	var InpEmail		= $("#frmFPEmail").val();
	if(jsTrim(InpEmail)== ""){
		$('#ErrFPEmail').text("Please fill the E-mail");
		$('#frmFPEmail').focus();
		$('#frmFPEmail').css("border", "1px solid #B94A48");
		return false;		
	}
	var Parameters = "rfrom=1&e="+InpEmail;
	MakePostRequest(base_path+'login/forgotpassword',Parameters,'json',fnForgotPassRes);
	return false;
}

function fnForgotPassRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			$('#ErrFPEmail').text(data.msg);
			return false;
		} else if(data.errcode==1){ 
			resetForm('frmNameForgotPass');
			$('#ErrForgotPassMsg').text(data.msg);
		}
	}
}