function fnShowProfileCont(VarDivShow) {
	var ArrProfileContList = ["DivContCompanyInfo","DivContDetailInfo","DivContAddressInfo","DivContBankInfo","DivContPwdInfo","DivContPaymentInfo","DivContProfileValidationInfo"];
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
	$("#"+VarDivShow).addClass('show');
}

function fnShowHideEndUserSub(VarType,VarDivShow) {
	var ArrProfileBasicList = ["divEditCompanyInfo","divShowCompanyInfo"];
	var ArrProfileContactList = ["divEditContactInfo","divShowContactInfo"];
	var ArrProfileAddressList = ["divAddEditAddressInfo","divShowAddressInfo"];
	var ArrProfileBankList = ["divEditBankInfo","divShowBankInfo"];
	var ArrProfilePaymentList = ["divEditPaymentInfo","divShowPaymentInfo"];
	var ArrProfileValidationList = ["divAddEditProfileValidationInfo","divShowProfileValidationInfo"];
	if(VarType==1) {
		var ArrFnalList	= ArrProfileBasicList;
	} else if(VarType==2) {
		var ArrFnalList	= ArrProfileContactList;
	} else if(VarType==3) {
		var ArrFnalList	= ArrProfileAddressList;
		fnResetAddressEditCont();
		$("#divAddressFormInfo").addClass('show');
		$("#divAddressDetInfo").addClass('hide');
	} else if(VarType==4) {
		var ArrFnalList	= ArrProfileBankList;
	} else if(VarType==5) {
		var ArrFnalList	= ArrProfilePaymentList;
	} else if(VarType==6) {
		var ArrFnalList	= ArrProfileValidationList;
		fnResetProfileEditCont();
		$("#divProfileValidationFormInfo").addClass('show');
		$("#divProfileValidationDetInfo").addClass('hide');
	}
	//Remove Class
	for(i=0;i<ArrFnalList.length;i++) {
		$("#"+ArrFnalList[i]).removeClass('show');
		$("#"+ArrFnalList[i]).removeClass('hide');
	}
	//Add Class
	for(i=0;i<ArrFnalList.length;i++) {
		if(VarDivShow!=ArrFnalList[i]) {
			$("#"+ArrFnalList[i]).addClass('hide');
		}
	}
	$("#"+VarDivShow).addClass('show');
}


function fnSaveCompanyDetails() {
	$('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').html('');
	var ProfileFormData							= false;
	var BusinessName							= $("#frmBusinessName").val();
	var EntityType								= $("#frmBusinessStatus").val();
	var NatureofBusiness						= $("#frmBusinessNature").val();
	var TINNumber								= $("#frmTinNumber").val();
	var CompanyPhone							= $("#frmCompanyPhone").val();	
	if(jsTrim(BusinessName)== ""){
		$('#ErrBusinessName').html("Please fill the Name");
		$('#frmBusinessName').focus();
		$('#frmBusinesssName').css("border", "1px solid #B94A48");
		return false;		
	}	
	if(jsTrim(EntityType)== ""){
		$('#ErrBusinessStatus').html("Please choose the business status");
		$('#frmBusinessStatus').focus();
		$('#frmBusinessStatus').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(NatureofBusiness)== ""){
		$('#ErrBusinessNature').html("Please fill the Nature of Business");
		$('#frmBusinessNature').focus();
		$('#frmBusinessNature').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(TINNumber)== ""){
		$('#ErrTinNumber').html("Please fill the TIN Number");
		$('#frmTinNumber').focus();
		$('#frmTinNumber').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(CompanyPhone)== ""){
		$('#ErrCompanyPhone').html("Please fill the Phone Number");
		$('#frmCompanyPhone').focus();
		$('#frmCompanyPhone').css("border", "1px solid #B94A48");
		return false;		
	}
	if (window.FormData){
		ProfileFormData								= new FormData();
		ProfileFormData.append("bn",BusinessName);
		ProfileFormData.append("et",EntityType);
		ProfileFormData.append("nob",NatureofBusiness);	
		ProfileFormData.append("tn",TINNumber);	
		ProfileFormData.append("p",CompanyPhone);
	}
	$.ajax({
		url 		: base_path+'seller/profile/updateCompanyBasicInfo',
		data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
		cache       : false,
		contentType : false,
		processData : false,
		type        : 'POST',
		success     : function(data, textStatus, jqXHR){
			data = jQuery.parseJSON(data);
			fnSaveCompanyDetailsRes(data);
		}
	});
	return false;
}

function fnSaveCompanyDetailsRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			$('#ErrCompanyEmail').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			$("#divSuccessBasicInfoMsg").removeClass('hide');
			$("#divSuccessBasicInfoMsg").html("Your Company Details has been saved at successfully!!!");
			//Refresh the Current Page
			fnRedirectPageTimeOut(window.location.href);
		}
	}
}

function fnSaveCompanyContactDetails() {
	$('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').html('');
	var ProfileFormData							= false;
	var ContactName								= $("#frmContactName").val();
	var ContactEmail							= $("#frmContactEmail").val();
	var ContactMobile							= $("#frmContactMobile").val();


	if(jsTrim(ContactName)== ""){
		$('#ErrContactName').html("Please fill contact Name");
		$('#frmContactName').focus();
		$('#frmContactName').css("border", "1px solid #B94A48");
		return false;		
	}	
	if(jsTrim(ContactEmail)== ""){
		$('#ErrContactEmail').html("Please fill the Contact E-Mail Id");
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
		$('#ErrContactMobile').html("Please fill the Contact Mobile Number");
		$('#frmContactMobile').focus();
		$('#frmContactMobile').css("border", "1px solid #B94A48");
		return false;		
	}	

	if (window.FormData){
		ProfileFormData								= new FormData();
		ProfileFormData.append("cn",ContactName);
		ProfileFormData.append("ce",ContactEmail);
		ProfileFormData.append("cm",ContactMobile);
	}
	$.ajax({
		url 		: base_path+'seller/profile/updateCompanyContactInfo',
		data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
		cache       : false,
		contentType : false,
		processData : false,
		type        : 'POST',
		success     : function(data, textStatus, jqXHR){
			data = jQuery.parseJSON(data);
			fnSaveCompanyContactDetailsRes(data);
		}
	});
	return false;
}

function fnSaveCompanyContactDetailsRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			$('#ErrCompanyEmail').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			$("#divSuccessContactInfoMsg").removeClass('hide');
			$("#divSuccessContactInfoMsg").html("Your Contact Details has been saved at successfully!!!");
			$('#divDispContactName').html($("#frmContactName").val());
			$('#divDispContactEmail').html($("#frmContactEmail").val());
			$('#divDispContactMobile').html($("#frmContactMobile").val());
		}
	}
}

function fnSaveCompanyAccountDetails() {
	$('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').html('');
	var ProfileFormData							= false;
	var BankName								= $("#frmBankName").val();
	var AccountName								= $("#frmAccountName").val();
	var AccountNumber							= $("#frmAccountNo").val();
	var BranchLocation							= $("#frmBranchLocation").val();
	var IFSCCode								= $("#frmIFSCCode").val();

	if(jsTrim(BankName)== ""){
		$('#ErrBankName').html("Please fill Bank Name");
		$('#frmBankName').focus();
		$('#frmBankName').css("border", "1px solid #B94A48");
		return false;		
	}	
	if(jsTrim(AccountName)== ""){
		$('#ErrAccountName').html("Please fill the Account Name");
		$('#frmAccountName').focus();
		$('#frmAccountName').css("border", "1px solid #B94A48");
		return false;		
	} 
	if(jsTrim(AccountNumber)== ""){
		$('#ErrAccountNumber').html("Please fill the Account Number");
		$('#frmAccountNumber').focus();
		$('#frmAccountNumber').css("border", "1px solid #B94A48");
		return false;		
	}	
	if(jsTrim(BranchLocation)== ""){
		$('#ErrBranchLocation').html("Please fill the Branch Location");
		$('#frmBranchLocation').focus();
		$('#frmBranchLocation').css("border", "1px solid #B94A48");
		return false;		
	}	
	if(jsTrim(IFSCCode)== ""){
		$('#ErrIFSCCode').html("Please fill the IFSC Code");
		$('#frmIFSCCode').focus();
		$('#frmIFSCCode').css("border", "1px solid #B94A48");
		return false;		
	}	

	if (window.FormData){
		ProfileFormData								= new FormData();
		ProfileFormData.append("bn",BankName);
		ProfileFormData.append("aname",AccountName);
		ProfileFormData.append("ano",AccountNumber);
		ProfileFormData.append("bl",BranchLocation);
		ProfileFormData.append("ic",IFSCCode);
	}
	$.ajax({
		url 		: base_path+'seller/profile/updateCompanyAccountInfo',
		data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
		cache       : false,
		contentType : false,
		processData : false,
		type        : 'POST',
		success     : function(data, textStatus, jqXHR){
			data = jQuery.parseJSON(data);
			fnSaveCompanyAccountDetailsRes(data);
		}
	});
	return false;
}

function fnSaveCompanyAccountDetailsRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			$('#ErrCompanyEmail').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			$("#divSuccessAccountInfoMsg").removeClass('hide');
			$("#divSuccessAccountInfoMsg").html("Your Account Details has been saved at successfully!!!");
			$('#divDispBankName').html($("#frmBankName").val());
			$('#divDispAccountName').html($("#frmAccountName").val());
			$('#divDispAccountNumber').html($("#frmAccountNo").val());
			$('#divDispAccountBranch').html($("#frmBranchLocation").val());
			$('#divDispIFSCCode').html($("#frmIFSCCode").val());
		}
	}
}

//Manage Address Information
function fnSaveCompanyAddressDetails() {
	$('.form-control').css("border", "1px solid #cccccc");
	$('div.herr').html('');
	var ProfileFormData							= false;
	var EndUserName								= $("#frmAddressName").val();
	var Address									= $("#frmAddressStreet").val();
	var Landmark								= $("#frmAddressLandkmark").val();
	var City									= $("#frmAddressCity").val();
	var State									= $("#frmAddressState").val();
	var PinCode									= $("#frmAddressPinCode").val();
	var Mobile									= $("#frmAddressMobileNo").val();
	if(jsTrim(EndUserName)== ""){
		$('#ErrAddressName').html("Please fill the Name");
		$('#frmAddressName').focus();
		$('#frmAddressName').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(Address)== ""){
		$('#ErrAddressStreet').html("Please fill the Address");
		$('#frmAddressStreet').focus();
		$('#frmAddressStreet').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(Landmark)== ""){
		$('#ErrAddressLandkmark').html("Please fill the landmark");
		$('#frmAddressLandkmark').focus();
		$('#frmAddressLandkmark').css("border", "1px solid #B94A48");
		return false;		
	}	
	if(jsTrim(City)== ""){
		$('#ErrAddressCity').html("Please fill the city");
		$('#frmAddressCity').focus();
		$('#frmAddressCity').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(State)== ""){
		$('#ErrAddressState').html("Please choose the state");
		$('#frmAddressState').focus();
		$('#frmAddressState').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(PinCode)== ""){
		$('#ErrAddressPinCode').html("Please fill the pincode");
		$('#frmAddressPinCode').focus();
		$('#frmAddressPinCode').css("border", "1px solid #B94A48");
		return false;		
	}
	if(jsTrim(Mobile)== ""){
		$('#ErrAddressMobileNo').html("Please fill the Mobile Number");
		$('#frmAddressMobileNo').focus();
		$('#frmAddressMobileNo').css("border", "1px solid #B94A48");
		return false;		
	} else if(jsTrim(Mobile).length<=9 || jsTrim(Mobile).length>=11) {
		$('#ErrAddressMobileNo').html("Please fill the Valid Mobile Number");
		$('#frmAddressMobileNo').focus();
		$('#frmAddressMobileNo').css("border", "1px solid #B94A48");
		return false;
	}		
	if (window.FormData){
		ProfileFormData								= new FormData();
		ProfileFormData.append("n",EndUserName);
		ProfileFormData.append("a",Address);
		ProfileFormData.append("l",Landmark);
		ProfileFormData.append("c",City);	
		ProfileFormData.append("s",State);	
		ProfileFormData.append("p",PinCode);	
		ProfileFormData.append("aid",GlbSelAddressId);
		ProfileFormData.append("m",Mobile);
	}
	$.ajax({
		url 		: base_path+'seller/profile/updateAddressInfo',
		data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
		cache       : false,
		contentType : false,
		processData : false,
		type        : 'POST',
		success     : function(data, textStatus, jqXHR){
			data = jQuery.parseJSON(data);
			fnSaveCompanyAddressDetailsRes(data);
			GlbSelAddressId='';
		}
	});
	return false;
}

function fnSaveCompanyAddressDetailsRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			$('#ErrAddressName').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			$("#divSuccessAddressInfoMsg").removeClass('hide');
			$("#divSuccessAddressInfoMsg").html("Your Address Details has been saved at successfully!!!");
			//Refresh the Current Page
			fnRedirectPageTimeOut(window.location.href);
		}
	}
}

function fnEditAddressInfo(VarAddressId,VarUserId) {
	var Parameters = "aid="+VarAddressId+"&uid="+VarUserId;
	GlbSelAddressId=VarAddressId;
	MakePostRequest(base_path+'seller/profile/getAddressInfo',Parameters,'json',fnEditAddressInfoRes);
}

function fnEditAddressInfoRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			//$('#ErrAddressName').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			var ObjAdressRes		= data.re;			
			$("#frmAddressCity").val(ObjAdressRes.c);
			$("#frmAddressName").val(ObjAdressRes.n);
			$("#frmAddressStreet").val(ObjAdressRes.a);
			$("#frmAddressLandkmark").val(ObjAdressRes.l);
			$("#frmAddressState").val(ObjAdressRes.sid);
			$("#frmAddressPinCode").val(ObjAdressRes.p);
			$("#frmAddressMobileNo").val(ObjAdressRes.m);			
			fnShowHideEndUserSub(3,'divAddEditAddressInfo');
			fnResetAddressEditCont();
			$("#divAddressDetInfo").addClass('hide');
			$("#divAddressFormInfo").addClass('show');
		}
	}
}

function fnDelAddressInfo(VarAddressId,VarUserId) {
	var Parameters = "aid="+VarAddressId;
	GlbSelAddressId=VarAddressId;
	MakePostRequest(base_path+'seller/profile/delAddressInfo',Parameters,'json',fnDelAddressInfoRes);
}

function fnDelAddressInfoRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			//$('#ErrAddressName').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			fnRedirectPageTimeOut(window.location.href);
		}
	}
}

function fnShowAddressInfo(VarAddressId,VarUserId) {
	var Parameters = "aid="+VarAddressId;
	MakePostRequest(base_path+'seller/profile/getAddressInfo',Parameters,'json',fnShowAddressInfoRes);
}

function fnShowAddressInfoRes(data){
	if(data!=''){ 
		if(data.errcode == '404') {
			fnCallSessionExpire();
			return false;
		} else if(data.errcode==-1){ 
			//$('#ErrAddressName').html(data.msg);
			return false;
		} else if(data.errcode==1){ 
			var ObjAdressRes		= data.re;			
			$("#divDispAddressCity").html(ObjAdressRes.c);
			$("#divDispAddressName").html(ObjAdressRes.n);
			$("#divDispAddressLandmark").html(ObjAdressRes.l);
			$("#divDispAddressState").html(ObjAdressRes.sname);
			$("#divDispAddressPincode").html(ObjAdressRes.p);
			$("#divDispAddressMobile").html(ObjAdressRes.m);
			$("#divDispAddressStatus").html(ObjAdressRes.as);			
			fnShowHideEndUserSub(3,'divAddEditAddressInfo');
			fnResetAddressEditCont();
			$("#divAddressDetInfo").addClass('show');
			$("#divAddressFormInfo").addClass('hide');
		}
	}
}

function fnResetAddressEditCont() {
	$("#divAddressDetInfo").removeClass('hide');
	$("#divAddressFormInfo").removeClass('hide');
	$("#divAddressDetInfo").removeClass('show');
	$("#divAddressFormInfo").removeClass('show');
}