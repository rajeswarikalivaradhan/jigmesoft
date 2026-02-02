var GlbSearchParam='';
var GlbSortOrder=''; var GlbColumnId='';
function fnSaveCompanyBasicInfo() {
    try{
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
        var frmBasicEstYear  						= $("#frmBasicEstYear").val();
        var CompanyEmail  						    = $("#frmBasicCompanyEmail").val();
        var Mobile  						    = $("#frmBasicCompanyMobile").val();
        var BasicName  						    = $("#frmBasicName").val();

        if(jsTrim(CompanyName)== ""){
            $('#ErrBasicCompanyName').text("Please fill the company name");
            $('#frmBasicCompanyName').focus();
            $('#frmBasicCompanyName').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(BasicName)== "") {
            $('#ErrBasicName').html("Please fill the name");
            $('#frmBasicName').focus();
            $('#frmBasicName').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(NoOfEmployee)=="") {
            $('#ErrBasicNoOfEmployee').text("Please fill No. of Employees");
            $('#BasicNoOfEmployee').focus();
            $('#BasicNoOfEmployee').css("border", "1px solid #B94A48");
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

        if(jsTrim(CompanyEmail)== "") {
            $('#ErrfrmBasicCompanyEmail').text("Please fill the E-mail Id");
            $('#frmBasicCompanyEmail').focus();
            $('#frmBasicCompanyEmail').css("border", "1px solid #B94A48");
            return false;
        }

        if(jsTrim(Address)== ""){
            $('#ErrBasicAddress').text("Please fill the address");
            $('#frmBasicAddress').focus();
            $('#frmBasicAddress').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(City)== ""){
            $('#ErrBasicCity').text("Please fill the City");
            $('#frmBasicCity').focus();
            $('#frmBasicCity').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(State)== ""){
            $('#ErrBasicState').text("Please fill the State");
            $('#frmBasicState').focus();
            $('#frmBasicState').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(Country)== ""){
            $('#ErrBasicCountry').text("Please fill the Country");
            $('#frmBasicCountry').focus();
            $('#frmBasicCountry').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(ZipCode)== ""){
            $('#ErrBasicZipcode').text("Please fill the Zip Code");
            $('#frmBasicZipcode').focus();
            $('#frmBasicZipcode').css("border", "1px solid #B94A48");
            return false;
        }

        if(jsTrim(Mobile) == "") {
            $('#ErrfrmBasicCompanyMobile').text("Please fill the Mobile No.");
            $('#frmBasicCompanyMobile').focus();
            $('#frmBasicCompanyMobile').css("border", "1px solid #B94A48");
            return false;
        }

        /*if(jsTrim(OwnershipFactory)== ""){
            $('#ErrBasicOwnershipFactory').text("Please fill the address");
            $('#frmBasicOwnershipFactory').focus();
            $('#frmBasicOwnershipFactory').css("border", "1px solid #B94A48");
            return false;
        }*/

        if (window.FormData){
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
            ProfileFormData.append("yex",frmBasicEstYear);
            ProfileFormData.append("cem",CompanyEmail);
            ProfileFormData.append("mo",Mobile);
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
                //console.log(data);
                //console.log('eid',data.eid);
                fnSaveCompanyBasicRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveCompanyBasicRes(data){
    if(data!='') {
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrBasicEmail').text(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbCompanyId   = data.cid;
            console.log(data,'data');
            fnRedirectPageTimeOut(base_path+GlbCAdminFdr+'company/addeditcompany/'+data.eid+"/1/");
        }
    }
}

function fnSearchCompany() {
    var CompanyName							    = $("#frmSrchCompanyName").val();
    var frmSrchComCode							= $("#frmSrchComCode").val();
    var Phone         							= $("#frmSrchContactMobile").val();
    var Email              						= $("#frmSrchContactEmail").val();
    var BusinessType       						= $("#frmSrchBusinessType").val();
    var City             						= $("#frmSrchContactCity").val();
    var State             						= $("#frmSrchContactState").val();
    var Country         					    = $("#frmSrchCountry").val();
    GlbSearchParam							    = "rfrom=1&n="+CompanyName+"&e="+Email+"&ccode="+frmSrchComCode+"&ctry="+Country+"&bt="+BusinessType+"&s="+State+"&c="+City+
        "&p="+Phone;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+GlbCAdminFdr+'company/managecompany',GlbSearchParam,'json',fnListCompanyRes);
}

function fnListCompany() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+GlbCAdminFdr+"company/managecompany",GlbSearchParam,"json",fnListCompanyRes);
}

function fnListCompanyRes(data) {
    if(data!='') {
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    var ListCount	= '<div style="font-weight:bold;">Number of Company(s) : '+data.cn+'</div>';
                    if(data.ct>0) {
                        console.log(data.re,'sdfosdfsdf');
                        $.each(data.re,function(index,value) {
                            PageContent=PageContent+'<tr><td><input type="checkbox" name="comp[]" class="allcbox" id="'+value.id+'" value="yes"></td>' +
                                '<td><a href="'+base_path+GlbCAdminFdr+'company/addeditcompany/'+escape(base64_encode(value.id))+'/">'+value.n+'</a></td>' +
                                '<td>'+value.pho+'</td>' +
                                '<td>'+value.u+'</td>'+
                                '<td>'+value.bt+'</td>' +
                                '<td>'+value.fo+'</td>' +
                                '<td>'+value.c+'</td>' +
                                '<td>'+value.s.statename+'</td>' +
                                '<td>'+value.ctn+'</td>' +
                                '<td>'+value.du+'</td>' +
                                '<td>'+value.stat+'</td>' +
                                '<td><a href="'+base_path+GlbCAdminFdr+'company/addeditcompany/'+encodeURIComponent(base64_encode(value.id))+'/edit'+'">' +
                                '<i class="fa fa-edit"></i>Edit</a>';
                            PageContent=PageContent+'</td></tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Company(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
//                PageContent	= PageContent+'</tbody></table>';

                if(data.pa!=undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                //$("#ResResult").html(PageContent);
                $('tbody').empty();
                $("#companyListTable").append(PageContent);
            }
        }
    }
}

function fnPaginationCompany(VarURL) {
    GlbSearchParam								= "rfrom=1";
    console.log(GlbSearchParam);
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,GlbSearchParam,'json',fnListCompanyRes);
}

function fnDeleteCompany(cid) {
    if(confirm("Are you want to delete this company?")) {
        var Parameters = "rfrom="+1+"&deletecompId="+cid;
        MakePostRequest(base_path+GlbCAdminFdr+'company/managecompany',Parameters,'json',fnDeleteCompanyRes);
    }
}
function fnDeleteCompanyRes(data) {
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnListCompany();
            }
        }
    }
}

//Factory Contact
var GlbContactId = '';
function fnSaveCompanyContact() {
    try {
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
            $('#ErrBasicContactName').html("Please fill the contact name");
            $('#frmBasicContactName').focus();
            $('#frmBasicContactName').css("border", "1px solid #B94A48");
            return false;
        } else if(jsTrim(ContactName)!= "" && !isAplpha(ContactName)) {
            $('#ErrBasicContactName').html("Please fill the valid contact name");
            $('#frmBasicContactName').focus();
            $('#frmBasicContactName').css("border", "1px solid #B94A48");
            return false;
        }
        if (jsTrim(ContactEmail) == "") {
            $('#ErrBasicContactEmail').html("Please fill the email id");
            $('#frmBasicContactEmail').focus();
            $('#frmBasicContactEmail').css("border", "1px solid #B94A48");
            return false;
        } else if (!isEmail(ContactEmail)) {
            $('#ErrBasicContactEmail').html("Invalid email id!");
            $('#frmBasicContactEmail').focus();
            $('#frmBasicContactEmail').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(ContactMobile)=="" && jsTrim(ContactPhone)=="") {
            $('#ErrBasicContactMobile').html("Please fill the mobile no./phone no.");
            $('#frmBasicContactMobile').focus();
            $('#frmBasicContactMobile').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(ContactMobile) != "" && !isPhoneNumber(ContactMobile)) {
            $('#ErrBasicContactMobile').html("Please fill the valid mobile no");
            $('#frmBasicContactMobile').focus();
            $('#frmBasicContactMobile').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(ContactPhone) != "" && !isPhoneNumber(ContactPhone)) {
            $('#ErrBasicContactPhone').html("Please fill the valid mobile no");
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
            $("#divSuccessContactInfoMsg").html("Factory Contact information has been saved at successfully!");
            resetForm('frmNameBasicContactInfo');
            fnListCompanyContact();
        }
    }
}

function fnListCompanyContact() {
    GlbSearchParam								= "rfrom=1&cid="+GlbCompanyId;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
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
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListCompanyContactRes);
}

function fnEditCompanyContactInfo(ContactId) {
    GlbContactId                                = ContactId;
    var Parameters								= "rfrom=1&cid="+GlbCompanyId+"&id="+ContactId;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCAdminFdr+'/company/getcompanycontactInfo',Parameters,'json',fnEditCompanyContactInfoRes);
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
            $('#ErrMachineFlag').html("Please fill the contact name");
            $('#frmMachineFlag').focus();
            $('#frmMachineFlag').css("border", "1px solid #B94A48");
            return false;
        } else if(parseInt(jsTrim(MachineFlag))== 1) {
            //alert("Test2");
            if (jsTrim(MachineType) == "") {
                $('#ErrMachineType').html("Please fill the email id");
                $('#frmMachineType').focus();
                $('#frmMachineType').css("border", "1px solid #B94A48");
                return false;
            }
        } else if(parseInt(jsTrim(MachineFlag))== 2) {
            //alert("Test1");
            if (jsTrim(TableType) == "") {
                $('#ErrTableType').html("Please fill the email id");
                $('#frmTableType').focus();
                $('#frmTableType').css("border", "1px solid #B94A48");
                return false;
            }
        }
        //alert("Test");
        if (jsTrim(MachineCount) == "") {
            $('#ErrMachineCount').html("Please fill the number of machine");
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
            $("#divSuccessMachineInfoMsg").html("Company Machine information has been saved at successfully!");
            resetForm('frmBasicMachineInfo');
            fnListCompanyMachine();
        }
    }
}


function fnListCompanyMachine() {
    GlbSearchParam								= "rfrom=1&cid="+GlbCompanyId;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
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
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListCompanyContactRes);
}

function fnEditCompanyMachineInfo(MachineId) {
    GlbMachineId                                = MachineId;
    var Parameters								= "rfrom=1&cid="+GlbCompanyId+"&id="+MachineId;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCAdminFdr+'/company/getcompanymachineInfo',Parameters,'json',fnEditCompanyMachineInfoRes);
}

function fnEditCompanyMachineInfoRes(data){
    if(data!=''){
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

$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SelectedIdObject = commonCheckbox();
        var checkBoxLength   = SelectedIdObject[1];
        console.log(checkBoxLength,'checkBoxLength');
        $('#ErrOption').html("");
        if (checkBoxLength == 0) { $('#frmItemStatus').html("Choose a Company"); }
        if (checkBoxLength >= 1) {
            $('#ErractivateFabTypeOpt').html("");
            var idJson = JSON.stringify(SelectedIdObject[0]);
            if (dropdownOpt == '1') { //Activate
                if (confirm('Do you want to Activate this company?')) {
                    var param = GlbSearchParam + "&actdeact=" + dropdownOpt + "&cid=" + idJson;
                    MakePostRequest(base_path + GlbCAdminFdr + 'company/act_deactivateCompany', param, 'json', fnChangeStatusRes);
                }
            }
            else if (dropdownOpt == '2') { //Deactivate
                if (confirm('Do you want to Deactivate this company?')) {
                    var param = GlbSearchParam + "&actdeact=" + dropdownOpt + "&cid=" + idJson;
                    MakePostRequest(base_path + GlbCAdminFdr + 'company/act_deactivateCompany', param, 'json', fnChangeStatusRes);
                }
            }
        }
    }
    else {
        $('#ErrOption').html("Select a Option");
    }
});

function fnChangeStatusRes(data) {
    if(data!='') {
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

//
    $('#companyListTable').on('click','th.sortable',function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        // var CompanyName							    = $("#frmSrchCompanyName").val();
        // var Mobile									= $("#frmSrchContactMobile").val();
        // var Phone         							= $("#frmSrchContactPhone").val();
        // var BusinessType       						= $("#frmSrchBusinessType").val();
        // var City             						= $("#frmSrchContactCity").val();
        // var State             						= $("#frmSrchContactState").val();
        // var Email              						= $("#frmSrchContactEmail").val();
        // var Country         					    = $("#frmSrchCountry").val();
        var param = GlbSearchParam+"&sortorder="+GlbSortOrder+"&colid="+GlbColumnId;
        MakePostRequest(base_path+GlbCAdminFdr+'company/managecompany',param,'json',fnListCompanyRes);
    });

    //
$('#btnChangeStatus').on('click',function () {
    var StatusOptSelVal                         = $('#frmItemStatus').val();
    $('#ErrItemStatus').text("");
    if(parseInt(StatusOptSelVal) > 0) {
        var ArrItemCheckBoxSel                  = commonCheckbox();
        var ObkChkSelVal                        = ArrItemCheckBoxSel[0];
        if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
            var StatusText                      = "Deactivate";
            if(StatusOptSelVal == '1') {
                var StatusText                  = "Activate";
            }
            var CompanyName							    = $("#frmSrchCompanyName").val();
            var frmSrchComCode									= $("#frmSrchComCode").val();
            var Phone         							= $("#frmSrchContactMobile").val();
            var Email              						= $("#frmSrchContactEmail").val();
            var BusinessType       						= $("#frmSrchBusinessType").val();
            var City             						= $("#frmSrchContactCity").val();
            var State             						= $("#frmSrchContactState").val();

            var Country         					    = $("#frmSrchCountry").val();
            var param							    = "rfrom=1&n="+CompanyName+"&e="+Email+"&ccode="+frmSrchComCode+"&ctry="+Country+"&bt="+BusinessType+"&s="+State+"&c="+City+
                "&p="+Phone+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;

            if(confirm('Do you want to '+StatusText+' this records?')) {
                MakePostRequest(base_path+GlbCAdminFdr+'company/act_deactivateCompany',param+"&actdeact="+StatusOptSelVal+"&cid="+
                    JSON.stringify(ObkChkSelVal),'json',fnListCompanyRes);
            }
        }
        else {
            //$('#ErrItemStatus').html("Select the Checkbox");
        }
    } else {
        $('#ErrOption').html("Select a Option");
    }
});