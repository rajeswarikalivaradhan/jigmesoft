function fnShowProfileCont(VarDivShow) {
    var ArrProfileContList = ["DivContBasicInfo","DivContPlantMachineInfo","DivContAddPlantMachineInfo","DivContContactInfo","DivContAddContactInfo"];
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
    var ArrProfileBasicList = ["divEditBasicInfo","divViewBasicInfo"];
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

function fnSaveCompanyBasicInfo() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').html('');
        var ProfileFormData							= false;
        var CompanyName    							= $("#frmBasicCompanyName").val();
        var BusinessType    						= $("#frmBasicBusinessType").val();
        var FactorySize								= $("#frmBasicFactorySize").val();
        var Address  							    = $("#frmBasicAddress").val();
        var NoOfMachine       					    = $("#frmBasicNoOfMachine").val();
        var City      								= $("#frmBasicCity").val();
        var State     								= $("#frmBasicState").val();
        var Country 							    = $("#frmBasicCountry").val();
        var ProductCapacity  					    = $("#frmBasicProductCapacity").val();
        var TurnOver  								= $("#frmBasicAnnualTurnOver").val();
        var NoOfEmployee    						= $("#frmBasicNoOfEmployee").val();
        var ZipCode  								= $("#frmBasicZipcode").val();
        var ContractWorker  					    = $("#frmBasicContractWorker").val();
        var OwnershipFactory  					    = $("#frmBasicOwnershipFactory").val();
        var MajorCustomer   						= $("#frmBasicMajorCustomer").val();
        var ExportCustomer							= $("#frmBasicMajorExportCustomer").val();
        var CompanyProfile  						= $("#frmBasicProfile").val();
        var BasicName                            = $("#frmBasicName").val();
        if(GlbCompanyId>=1) {CompanyId=GlbCompanyId;}
        if(jsTrim(BasicName)== "") {
            $('#ErrfrmBasicName').text("Please fill the name");
            $('#frmBasicName').focus();
            $('#frmBasicName').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(CompanyName)== "") {
            $('#ErrBasicCompanyName').text("Please fill the company name");
            $('#frmBasicCompanyName').focus();
            $('#frmBasicCompanyName').css("border", "1px solid #B94A48");
            return false;
        }
        /*if(jsTrim(BusinessType)== ""){
            $('#ErrBasicBusinessType').text("Please choose the business type");
            $('#frmBasicBusinessType').focus();
            $('#frmBasicBusinessType').css("border", "1px solid #B94A48");
            return false;
        }*/
        /*if(jsTrim(Website)!= "" && !IsUrl(Website)){
            $('#ErrBasicWebsite').text("Please fill the valid website");
            $('#frmBasicWebsite').focus();
            $('#frmBasicWebsite').css("border", "1px solid #B94A48");
            return false;
        }*/
        if(jsTrim(Address)== ""){
            $('#ErrBasicAddress').text("Please fill the address");
            $('#frmBasicAddress').focus();
            $('#frmBasicAddress').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(City)== ""){
            $('#ErrBasicCity').text("Please fill the address");
            $('#frmBasicCity').focus();
            $('#frmBasicCity').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(State)== ""){
            $('#ErrBasicState').text("Please fill the address");
            $('#frmBasicState').focus();
            $('#frmBasicState').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(Country)== ""){
            $('#ErrBasicCountry').text("Please fill the address");
            $('#frmBasicCountry').focus();
            $('#frmBasicCountry').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(ZipCode)== ""){
            $('#ErrBasicZipcode').text("Please fill the address");
            $('#frmBasicZipcode').focus();
            $('#frmBasicZipcode').css("border", "1px solid #B94A48");
            return false;
        }
        /*if(jsTrim(OwnershipFactory)== ""){
            $('#ErrBasicOwnershipFactory').text("Please fill the address");
            $('#frmBasicOwnershipFactory').focus();
            $('#frmBasicOwnershipFactory').css("border", "1px solid #B94A48");
            return false;
        }*/
        if (window.FormData) {
            ProfileFormData								= new FormData();
            ProfileFormData.append("n",BasicName);
            ProfileFormData.append("cn",CompanyName);
            ProfileFormData.append("bt",BusinessType);
            ProfileFormData.append("fz",FactorySize);
            ProfileFormData.append("a",Address);
            ProfileFormData.append("nm",NoOfMachine);
            ProfileFormData.append("c",City);
            ProfileFormData.append("s",State);
            ProfileFormData.append("ctry",Country);
            ProfileFormData.append("pc",ProductCapacity);
            ProfileFormData.append("to",TurnOver);
            ProfileFormData.append("ne",NoOfEmployee);
            ProfileFormData.append("zc",ZipCode);
            ProfileFormData.append("cw",ContractWorker);
            ProfileFormData.append("of",OwnershipFactory);
            ProfileFormData.append("mc",MajorCustomer);
            ProfileFormData.append("ec",ExportCustomer);
            ProfileFormData.append("cp",CompanyProfile);
            ProfileFormData.append("cid",GlbCompanyId);
        }
        $.ajax({
            url 		: base_path+GlbCAdminFdr+'company/updateCompanyBasicInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = jQuery.parseJSON(data);
                fnSaveCompanyBasicRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveCompanyBasicRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrBasicEmail').html(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbCompanyId   = data.cid;

                fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'/company/addeditcompany/'+data.eid+"/1/");

        }
    }
}

function fnSearchCompany() {
    var CompanyName							    = $("#frmSrchCompanyName").val();
    var Mobile									= $("#frmSrchContactMobile").val();
    var Phone         							= $("#frmSrchContactPhone").val();
    var BusinessType       						= $("#frmSrchBusinessType").val();
    var City             						= $("#frmSrchContactCity").val();
    var State             						= $("#frmSrchContactState").val();
    var Email              						= $("#frmSrchContactEmail").val();
    var Country         					= $("#frmSrchCountry").val();
    GlbSearchParam							    = "rfrom=1&n="+CompanyName+"&e="+Email+"&m="+Mobile+"&ctry="+Country+"&bt="+BusinessType+"&s="+State+"&c="+City+"&p="+Phone;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCAdminFdr+'company/managecompany',GlbSearchParam,'json',fnListCompanyRes);
}

function fnListCompany() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+GlbCAdminFdr+'company/managecompany',GlbSearchParam,'json',fnListCompanyRes);
}

function fnListCompanyRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Company(s) : '+data.cn+'</div>';
                    PageContent	= "<table id='example1' class='table table-bordered table-hover'><thead><tr><th>Company Name</th><th>Business Type</th><th>Factory Ownership</th><th>Username</th><th>Password</th><th>City</th><th>State</th><th>Country</th><th>Last Update</th><th>Action</th></tr></thead><tbody>";
                    if(data.ct>0) {
                        $.each(data.re,function(index,value){
                            PageContent=PageContent+'<tr><td><a href="'+base_path+GlbCompanyFdr+'company/addeditcompany/'+escape(base64_encode(value.id))+'/">'+value.n+'</a></td><td>'+value.bt+'</td><td>'+value.fo+'</td><td>'+value.u+'</td><td>'+value.p+'</td><td>'+value.c+'</td><td>'+value.s+'</td><td>'+value.ctn+'</td><td>'+value.du+'</td><td>';
                            PageContent=PageContent+'&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDeleteCompany('+value.id+')"><i class="fa fa-trash-o"></i> Delete</a>';
                            PageContent=PageContent+'</td></tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Company(s) found</td></tr>';
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
function fnPaginationCompany(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListCompanyRes);
}

function fnDeleteCompany(Id) {
    if(confirm("Are you want to delete this company?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCAdminFdr+'company/delCompany',Parameters,'json',fnDeleteCompanyRes);
    }
}

function fnDeleteCompanyRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchCompany();
            }
        }
    }
}

//Factory Contact
var GlbContactId = '';
function fnSaveCompanyContact() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').html('');

        var ProfileFormData							= false;
        var ContactName    							= $("#frmBasicContactName").val();
        var ContactEmail  						    = $("#frmBasicContactEmail").val();
        var ContactMobile   						= $("#frmBasicContactMobile").val();
        var ContactPhone						    = $("#frmBasicContactPhone").val();
        var ContactDesignation 		    	        = $("#frmBasicContactDesignation").val();
        var ContactId								= '';
        if(GlbContactId>=1) {ContactId=GlbContactId;}
        if(jsTrim(ContactName)== ""){
            $('#ErrBasicContactName').text("Please fill the contact name");
            $('#frmBasicContactName').focus();
            $('#frmBasicContactName').css("border", "1px solid #B94A48");
            return false;
        } else if(jsTrim(ContactName)!= "" && !isAplpha(ContactName)) {
            $('#ErrBasicContactName').text("Please fill the valid contact name");
            $('#frmBasicContactName').focus();
            $('#frmBasicContactName').css("border", "1px solid #B94A48");
            return false;
        }
        if (jsTrim(ContactEmail) == "") {
            $('#ErrBasicContactEmail').text("Please fill the email id");
            $('#frmBasicContactEmail').focus();
            $('#frmBasicContactEmail').css("border", "1px solid #B94A48");
            return false;
        } else if (!isEmail(ContactEmail)) {
            $('#ErrBasicContactEmail').text("Invalid email id!");
            $('#frmBasicContactEmail').focus();
            $('#frmBasicContactEmail').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(ContactMobile)=="" && jsTrim(ContactPhone)=="") {
            $('#ErrBasicContactMobile').text("Please fill the mobile no./phone no.");
            $('#frmBasicContactMobile').focus();
            $('#frmBasicContactMobile').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(ContactMobile) != "" && !isPhoneNumber(ContactMobile)) {
            $('#ErrBasicContactMobile').text("Please fill the valid mobile no");
            $('#frmBasicContactMobile').focus();
            $('#frmBasicContactMobile').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(ContactPhone) != "" && !isPhoneNumber(ContactPhone)) {
            $('#ErrBasicContactPhone').text("Please fill the valid mobile no");
            $('#frmBasicContactPhone').focus();
            $('#frmBasicContactPhone').css("border", "1px solid #B94A48");
            return false;
        }

        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("cn",ContactName);
            ProfileFormData.append("ce",ContactEmail);
            ProfileFormData.append("cm",ContactMobile);
            ProfileFormData.append("cp",ContactPhone);
            ProfileFormData.append("cd",ContactDesignation);
            ProfileFormData.append("cid",GlbCompanyId);
            ProfileFormData.append("id",ContactId);
        }
        $.ajax({
            url 		: base_path+GlbCAdminFdr+'company/updateCompanyContactInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = jQuery.parseJSON(data);
                fnSaveCompanyContactRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveCompanyContactRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            ///$('#ErrBasicEmail').html(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbFactoryId   = data.fid;
            $("#divSuccessContactInfoMsg").removeClass('hide');
            $("#divSuccessContactInfoMsg").text("Factory Contact information has been saved at successfully!");
            resetForm('frmNameBasicContactInfo');
            fnListCompanyContact();
        }
    }
}

function fnListCompanyContact() {
    GlbSearchParam								= "rfrom=1&cid="+GlbCompanyId;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCAdminFdr+'company/managecompanycontact',GlbSearchParam,'json',fnListCompanyContactRes);
}

function fnListCompanyContactRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Contact(s) : '+data.cn+'</div>';
                    PageContent	= "<table id='example1' class='table table-bordered table-hover'><thead><tr><th>Employee Name</th><th>E-Mail Id</th><th>Mobile</th><th>Phone</th><th>Designation</th><th>Action</th></tr></thead><tbody>";
                    if(data.ct>0) {
                        $.each(data.re,function(index,value){
                            PageContent=PageContent+'<tr><td>'+value.cn+'</td><td>'+value.e+'</td><td>'+value.m+'</td><td>'+value.p+'</td><td>'+value.d+'</td><td><a href="javascript:void(0);" onclick="fnEditCompanyContactInfo('+value.id+')">Edit</a></td>';
                            PageContent=PageContent+'</tr>';
                        });
                    }
                    $("#ResContactListCnt").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Contact(s) found</td></tr>';
                    $("#ResContactList").html('');
                }
                PageContent	= PageContent+'</tbody></table>';
                if(data.pa!=undefined) {
                    $("#ResContactPagination").html(base64_decode(data.pa));
                }
                $("#ResContactList").html(PageContent);
            }
        }
    }
}

function fnPaginationCompanyContact(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListCompanyContactRes);
}

function fnEditCompanyContactInfo(ContactId) {
    GlbContactId                                = ContactId;
    var Parameters								= "rfrom=1&cid="+GlbCompanyId+"&id="+ContactId;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCAdminFdr+'company/getcompanycontactInfo',Parameters,'json',fnEditCompanyContactInfoRes);
}

function fnEditCompanyContactInfoRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnShowProfileCont('DivContAddContactInfo');
                $("#frmBasicContactName").val(data.n);
                $("#frmBasicContactEmail").val(data.e);
                $("#frmBasicContactMobile").val(data.m);
                $("#frmBasicContactPhone").val(data.p);
                $("#frmBasicContactDesignation").val(data.d);
            }
        }
    }
}

function fnChangeMachineFlag(MachineFlag) {
    if(MachineFlag==1) {
        $("#divMachineTypeInfo").removeClass('hide');
        $("#divMachineTypeInfo").addClass('show');
        $("#divTableTypeInfo").addClass('hide');
    } else if(MachineFlag==2) {
        $("#divTableTypeInfo").removeClass('hide');
        $("#divTableTypeInfo").addClass('show');
        $("#divMachineTypeInfo").addClass('hide');
    }
}

var GlbMachineId='';
function fnSaveCompanyMachine() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').html('');

        var ProfileFormData							= false;
        var MachineFlag    							= $("#frmMachineFlag").val();
        var MachineType  						    = $("#frmMachineType").val();
        var TableType          						= $("#frmTableType").val();
        var MachineCount    					    = $("#frmMachineCount").val();
        var MachineId								= '';
        if(GlbMachineId>=1) {MachineId=GlbMachineId;}
        if(jsTrim(MachineFlag)== ""){
            $('#ErrMachineFlag').text("Please fill the contact name");
            $('#frmMachineFlag').focus();
            $('#frmMachineFlag').css("border", "1px solid #B94A48");
            return false;
        } else if(parseInt(jsTrim(MachineFlag))== 1) {
            //alert("Test2");
            if (jsTrim(MachineType) == "") {
                $('#ErrMachineType').text("Please fill the email id");
                $('#frmMachineType').focus();
                $('#frmMachineType').css("border", "1px solid #B94A48");
                return false;
            }
        } else if(parseInt(jsTrim(MachineFlag))== 2) {
            //alert("Test1");
            if (jsTrim(TableType) == "") {
                $('#ErrTableType').text("Please fill the email id");
                $('#frmTableType').focus();
                $('#frmTableType').css("border", "1px solid #B94A48");
                return false;
            }
        }
        //alert("Test");
        if (jsTrim(MachineCount) == "") {
            $('#ErrMachineCount').text("Please fill the number of machine");
            $('#frmMachineCount').focus();
            $('#frmMachineCount').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("mf",MachineFlag);
            ProfileFormData.append("mt",MachineType);
            ProfileFormData.append("tt",TableType);
            ProfileFormData.append("mc",MachineCount);
            ProfileFormData.append("cid",GlbCompanyId);
            ProfileFormData.append("id",MachineId);
        }
        $.ajax({
            url 		: base_path+GlbCAdminFdr+'company/updateCompanyMachineInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = jQuery.parseJSON(data);
                fnSaveCompanyMachineRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveCompanyMachineRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            ///$('#ErrBasicEmail').html(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbFactoryId   = data.fid;
            $("#divSuccessMachineInfoMsg").removeClass('hide');
            $("#divSuccessMachineInfoMsg").text("Company Machine information has been saved at successfully!");
            resetForm('frmBasicMachineInfo');
            fnListCompanyMachine();
        }
    }
}


function fnListCompanyMachine() {
    GlbSearchParam								= "rfrom=1&cid="+GlbCompanyId;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCAdminFdr+'company/managecompanymachine',GlbSearchParam,'json',fnListCompanyMachineRes);
}

function fnListCompanyMachineRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Machine(s) : '+data.cn+'</div>';
                    PageContent	= "<table id='example1' class='table table-bordered table-hover'><thead><tr><th>Type</th><th>Name</th><th>Count</th><th>Last Update<th>Action</th></tr></thead><tbody>";
                    if(data.ct>0) {
                        $.each(data.re,function(index,value){
                            PageContent=PageContent+'<tr><td>'+value.mf+'</td><td>'+value.mt+'</td><td>'+value.c+'</td><td>'+value.du+'</td><td><a href="javascript:void(0);" onclick="fnEditCompanyMachineInfo('+value.id+')">Edit</a></td>';
                            PageContent=PageContent+'</tr>';
                        });
                    }
                    $("#ResMachineListCnt").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Contact(s) found</td></tr>';
                    $("#ResContactList").html('');
                }
                PageContent	= PageContent+'</tbody></table>';
                if(data.pa!=undefined) {
                    $("#ResMachinePagination").html(base64_decode(data.pa));
                }
                $("#ResMachineList").html(PageContent);
            }
        }
    }
}

function fnPaginationCompanyContact(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListCompanyContactRes);
}

function fnEditCompanyMachineInfo(MachineId) {
    GlbMachineId                                = MachineId;
    var Parameters								= "rfrom=1&cid="+GlbCompanyId+"&id="+MachineId;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCAdminFdr+'company/getcompanymachineInfo',Parameters,'json',fnEditCompanyMachineInfoRes);
}

function fnEditCompanyMachineInfoRes(data){
    if(data!='') {
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnShowProfileCont('DivContAddPlantMachineInfo');
                $("#frmMachineFlag").val(data.mf);
                fnChangeMachineFlag(data.mf);
                if(data.mf==1) {
                    $("#frmMachineType").val(data.mt);
                } else {
                    $("#frmTableType").val(data.mt);
                }
                $("#frmMachineCount").val(data.nom);
            }
        }
    }
}
