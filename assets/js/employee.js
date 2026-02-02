function fnShowProfileCont(VarDivShow) {
    var ArrProfileContList = ["DivContBasicInfo","DivContPwdInfo","DivContUsernameInfo"];
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
    if(VarDivShow=="DivContAddressInfo") {
        fnShowHideEndUserSub(2,'divShowAddressInfo');
    }
    $("#"+VarDivShow).addClass('show');
}

function fnShowHideEndUserSub(VarType,VarDivShow) {
    var ArrProfileBasicList = ["divEditBasicInfo","divShowBasicInfo"];
    if(VarType==1) {
        var ArrFnalList	= ArrProfileBasicList;
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

function fnSearchEmployee() {
    var ContactName							    = $("#frmSrchContactName").val();
    var Email									= $("#frmSrchEmailId").val();
    var Mobile      							= $("#frmSrchMobileNo").val();
    var ProfilePermission						= $("#frmSrchProfileRole").val();
    GlbSearchParam							    = "rfrom=1&n="+ContactName+"&e="+Email+"&m="+Mobile+"&pp="+ProfilePermission;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+'profile/manageemployee',GlbSearchParam,'json',fnListEmployeeRes);
}

function fnListEmployee() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+'profile/manageemployee',GlbSearchParam,'json',fnListEmployeeRes);
}

function fnListEmployeeRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Employee(s) : '+data.cn+'</div>';
                    PageContent = "<table id='example1' class='table table-bordered table-hover'><thead><tr><th>Name</th><th>E-Mail Id</th><th>Password</th><th>Designation</th><th>Mobile No</th><th>Recent <br />Update </th></tr></thead><tbody>";
                    if(data.ct>0) {
                        $.each(data.re,function(index,value){
                            PageContent=PageContent+'<tr><td><a href="'+base_path+'profile/addeditemployee/'+escape(base64_encode(value.id))+'/">'+value.n+'</a></td><td>'+value.e+'</td><td>'+value.p+'</td><td>'+value.d+'</td><td>'+value.m+'</td><td>'+value.du+'</td>';                            
                            PageContent=PageContent+'</td>';
                            PageContent=PageContent+'</tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No User(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                PageContent	= PageContent+'</tbody></table>';
                if(data.pa!=undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $("#ResResult").html(PageContent);
            }
        }
    }
}

var GlbSearchParam='';
function fnPaginationEmployee(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListEmployeeRes);
}

function fnDeleteAdminUser(Id) {
    if(confirm("Are you want to delete this employee?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+'profile/delEmployee',Parameters,'json',fnDeleteAdminUserRes);
    }
}

function fnDeleteAdminUserRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchEmployee();
            }
        }
    }
}

function fnSaveEmployee() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').html('');
        var ProfileFormData							= false;
        var Name        							= $("#frmBasicContactName").val();
        var Gender									= $('input[name="frmBasicGender"]:checked').val();
        var Email									= $("#frmBasicEmail").val();
        var Mobile  								= $("#frmBasicMobile").val();
        var Designation 							= $("#frmBasicDesignation").val();
        var ProfileRole 							= $("#frmBasicProfilePermission").val();
        var WeChatId  								= $("#frmBasicWeChatId").val();
        var SkypeId 							    = $("#frmBasicSkypeId").val();

        var UserId								    = '';
        if(GlbUserId>=1) {UserId=GlbUserId;}
        if(jsTrim(Name)== ""){
            $('#ErrBasicContactName').html("Please fill the name");
            $('#frmBasicContactName').focus();
            $('#frmBasicContactName').css("border", "1px solid #B94A48");
            return false;
        }
        if(typeof(Gender)== "undefined"){
            $('#ErrBasicGender').html("Please choose the gender");
            $('#frmBasicGender1').focus();
            return false;
        }
        if($("#frmBasicEmail").length) {
            if (jsTrim(Email) == "") {
                $('#ErrBasicEmail').html("Please fill the email id");
                $('#frmBasicEmail').focus();
                $('#frmBasicEmail').css("border", "1px solid #B94A48");
                return false;
            } else if (!isEmail(Email)) {
                $('#ErrBasicEmail').html("Invalid email id!");
                $('#frmBasicEmail').focus();
                $('#frmBasicEmail').css("border", "1px solid #B94A48");
                return false;
            }
        }
        if(jsTrim(Mobile)== ""){
            $('#ErrBasicMobile').html("Please fill the mobile no");
            $('#frmBasicMobile').focus();
            $('#frmBasicMobile').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(Designation)== ""){
            $('#ErrBasicDesignation').html("Please fill the designation");
            $('#frmBasicDesignation').focus();
            $('#frmBasicDesignation').css("border", "1px solid #B94A48");
            return false;
        }
        if($("#frmBasicProfilePermission").length){
            if(jsTrim(ProfileRole)== "") {
                $('#ErrBasicProfilePermission').html("Please choose the profile role");
                $('#frmBasicProfilePermission').focus();
                $('#frmBasicProfilePermission').css("border", "1px solid #B94A48");
                return false;
            }
        }
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("n",Name);
            ProfileFormData.append("e",Email);
            ProfileFormData.append("g",Gender);
            ProfileFormData.append("m",Mobile);
            ProfileFormData.append("d",Designation);
            ProfileFormData.append("p",ProfileRole);
            ProfileFormData.append("wid",WeChatId);
            ProfileFormData.append("sid",SkypeId);
            ProfileFormData.append("id",UserId);
        }
        $.ajax({
            url 		: base_path+'profile/updateEmployee',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = jQuery.parseJSON(data);
                fnSaveEmployeeRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveEmployeeRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrBasicEmail').html(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbUserId   = data.uid;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").html("User profile created successfully!");
            fnRedirectPageTimeOut(base_path+'profile/addeditemployee/'+data.euid);
        }
    }
}

function fnEmployeeResetPassword() {
    var Parameters = "uid="+GlbUserId;
    MakePostRequest(base_path+'profile/employeeResetPassword',Parameters,'json',fnEmployeeResetPasswordRes);
}

function fnEmployeeResetPasswordRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            //$('#ErrAddressName').html(data.msg);
            return false;
        } else if(data.errcode==1){
            $("#divSuccessPasswordInfoMsg").removeClass('hide');
            $("#divSuccessPasswordInfoMsg").html("New Password has been sent!");
        }
    }
}