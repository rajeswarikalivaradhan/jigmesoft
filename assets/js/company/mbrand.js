var GlbSearchParam='';
 var GlbSortOrder=''; var GlbColumnId='';
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

function fnSearchBrand () {
    var frmSrchBrand     							= $("#frmSrchBrand").val();

    var Status        							= $("#frmSrchBrandStatus").val();
    GlbSearchParam								    = "rfrom=1&br="+frmSrchBrand+"&s="+Status;
    MakePostRequest(base_path+GlbCompanyFdr+'mbrand/manage',GlbSearchParam,'json',fnListBrandsRes);

}
function fnSaveBuyerInfo() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData							    = '';
        var frmBasicBrand     							= $("#frmBasicBrand").val();
        var frmBasicBuyer                               = $("#frmBasicBuyerId").val();
        var frmBasicContactName     					= $("#frmBasicContactName").val();
        var frmBasicEmail     							= $("#frmBasicEmail").val();
        var frmBasicMobile     							= $("#frmBasicMobile").val();
        var frmBasicContactNo     						= $("#frmBasicContactNo").val();
        var frmBasicAddress     						= $("#frmBasicAddress").val();
        var frmBasicDesignation     					= $("#frmBasicDesignation").val();
        var Status        							    = $("#frmBasicStatus").val();

        if(jsTrim(Status)== ""){
            $('#ErrBasicStatus').text("Please select the status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("byr",frmBasicBuyer);
            ProfileFormData.append("by",frmBasicBrand);
            ProfileFormData.append("cn",frmBasicContactName);
            ProfileFormData.append("e",frmBasicEmail);
            ProfileFormData.append("m",frmBasicMobile);
            ProfileFormData.append("p",frmBasicContactNo);
            ProfileFormData.append("a",frmBasicAddress);
            ProfileFormData.append("d",frmBasicDesignation);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'mbrand/updateBrandInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveBuyerRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveBuyerRes(data) {
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            //$('#ErrBasicMethodName').html(data.msg);
            return false;
        } else if(data.errcode==1){
            console.log(data,'data');
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Brand has updated at successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mbrand/addedit/'+data.eid);
        }
    }
}

function fnListBrands() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam								    = "rfrom=1";
    $("#ResResult").text("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'mbrand/manage',GlbSearchParam,'json',fnListBrandsRes);
}

function fnListBrandsRes(data) {
    console.log(data.re,'data');
    if(data!='') {
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Record(s) : '+data.cn+'</div>';
                    if(data.ct>0) {
                        $.each(data.re,function(index,value) {
                            PageContent=PageContent+'<tr>' +
                                '<td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>' +
                                '<td><a href="'+base_path+GlbCompanyFdr+'mbrand/addedit/'+encodeURIComponent(base64_encode(value.id))+'/">'+value.brand+'</a></td>' +
                                '<td>'+value.contactname+'</td>' +
                                '<td>'+value.s+'</td>' +
                                '<td><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDeleteBrand('+value.id+')">Delete</a></td>';
                            PageContent=PageContent+'</tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if(data.pa!=undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $('tbody').empty();
                $('#brandTblList').append(PageContent);
            }
        }
    }
}

function fnDeleteBrand(buyerid) {
    if(confirm("Are you want to delete this buyer?")) {
        var Parameters = GlbSearchParam+"&id="+buyerid;
        MakePostRequest(base_path+GlbCompanyFdr+'mbrand/delBuyerInfo',Parameters,'json',fnDeleteBrandRes);
    }
}

function fnDeleteBrandRes(data) {
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnListBrands();
            }
        }
    }

}
function fnChangeStatusRes(data) {
    if(data!='') {
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchBrand();
            }
        }
    }
}

    $('#brandTblList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);

        
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        var frmSrchBrand     							= $("#frmSrchBrand").val();
        var Status        							= $("#frmSrchBrandStatus").val();
        GlbSearchParam							    = "rfrom=1&br="+frmSrchBrand+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;

        MakePostRequest(base_path+GlbCompanyFdr+'mbrand/manage',GlbSearchParam,'json',fnListBrandsRes);

    });


    $('#btnChangeStatus').on('click',function () {
        var dropdownOpt                                 = $('#frmItemStatus').val();
        if(dropdownOpt > 0) {
            var SewTypeIdObject = commonCheckbox();
            var checkBoxLength = SewTypeIdObject[1];
            var cboxObj = SewTypeIdObject[0];
            $('#ErrItemStatus').text("");
            if(checkBoxLength == 0) {
                $('#ErrItemStatus').text("Choose a brand");
            }
            if (checkBoxLength >= 1) {
                $('#ErrItemStatus').text("");
                var companyid_json = JSON.stringify(cboxObj);
                var frmSrchBrand     							= $("#frmSrchBrand").val();
                var Status        							= $("#frmSrchBrandStatus").val();

                if (dropdownOpt == '1') { //Activate
                    if(confirm('Do you want to activate this brand?')) {
                        GlbSearchParam							    = "rfrom=1&br="+frmSrchBrand+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+ "&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mbrand/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
                else if (dropdownOpt == '2') { //Deactivate
                    if(confirm('Do you want to Deactivate this brand?')) {
                        GlbSearchParam							    = "rfrom=1&br="+frmSrchBrand+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+ "&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mbrand/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
            }
        }
        else {
            $('#ErrItemStatus').text("Select a Option");
        }

    });

function fnPaginationBrand(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');

    MakePostRequest(VarURL,Parameters,'json',fnListRes);
}