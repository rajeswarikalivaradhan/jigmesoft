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
            $('#ErrBasicCity').text("Please fill City");
            $('#frmBasicCity').focus();
            $('#frmBasicCity').css("border", "1px solid #B94A48");
            return false;
        }

        if(jsTrim(State)== ""){
            $('#ErrBasicState').text("Please fill the state");
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

        /*if(jsTrim(OwnershipFactory)== ""){
            $('#ErrBasicOwnershipFactory').text("Please fill the address");
            $('#frmBasicOwnershipFactory').focus();
            $('#frmBasicOwnershipFactory').css("border", "1px solid #B94A48");
            return false;
        }*/
        if (window.FormData) {
            ProfileFormData								= new FormData();
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
        }

        $.ajax({
            url 		: base_path+GlbCompanyFdr+'/profile/updateProfile',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR) {
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

                //fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'profile');

        }
    }
}