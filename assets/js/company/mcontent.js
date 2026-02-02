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

function fnSearchContent() {
    var Content          				        = $("#frmSrchContentName").val();
    var Status          						= $("#frmSrchContentStatus").val();
    GlbSearchParam							    = "rfrom=1&cn="+Content+"&s="+Status;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'mcontent/managecontent',GlbSearchParam,'json',fnListContentRes);
}

function fnListContent() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'mcontent/managecontent',GlbSearchParam,'json',fnListContentRes);
}

function fnListContentRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Record(s) : '+data.cn+'</div>';
                    if(data.ct>0) {
                        $.each(data.re,function(index,value){
                            PageContent=PageContent+'<tr><td><input type="checkbox" id="'+value.id+'" class="allcbox"> </td><td><a href="'+base_path+GlbCompanyFdr+'mcontent/addedit/'+encodeURIComponent(base64_encode(value.id))+'/">'+value.n+'</a></td><td>'+value.s+'</td><td>'+value.ub+'</td><td>'+value.du+'</td><td><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDeleteContent('+value.id+')">Delete</a></td>';
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
                $("#tableList").append(PageContent);
            }
        }
    }
}

function fnPaginationContent(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListContentRes);
}

function fnDeleteContent(Id) {
    if(confirm("Are you want to delete this content?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'mcontent/delContentInfo',Parameters,'json',fnDeleteContentRes);
    }
}

function fnDeleteContentRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchContent();
            }
        }
    }
}

function fnSaveContentInfo() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').html('');
        var ProfileFormData							= false;
        var Content     						    = $("#frmBasicContentName").val();
        var Status        							= $("#frmBasicStatus").val();
        if(jsTrim(Content)== ""){
            $('#ErrBasicContentName').text("Please fill the content");
            $('#frmBasicContentName').focus();
            $('#frmBasicContentName').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(Status)== ""){
            $('#ErrBasicStatus').text("Please select the status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("cn",Content);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'/mcontent/updateContentInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = jQuery.parseJSON(data);
                fnSaveContentRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveContentRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrBasicContent').html(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Content has updated at successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mcontent/addedit/'+data.eid);
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
                fnSearchContent();
            }
        }
    }
}

$( document ).ready(function() {
    $('#tableList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);

        
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];

        var Content          				        = $("#frmSrchContentName").val();
        var Status          						= $("#frmSrchContentStatus").val();

        GlbSearchParam							    = "rfrom=1&cn="+Content+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;
        MakePostRequest(base_path+GlbCompanyFdr+'mcontent/managecontent',GlbSearchParam,'json',fnListContentRes);

    });
    

    $('#btnChangeStatus').on('click',function () {
        var dropdownOpt                                 = $('#frmItemStatus').val();
        if(dropdownOpt > 0) {
            var SewTypeIdObject = commonCheckbox();
            var checkBoxLength = SewTypeIdObject[1];
            var cboxObj = SewTypeIdObject[0];
            $('#ErrItemStatus').text("");
            if(checkBoxLength == 0) {
                $('#ErrItemStatus').text("Choose a content");
            }
            if (checkBoxLength >= 1) {
                $('#ErrItemStatus').text("");
                var companyid_json = JSON.stringify(cboxObj);
                var Content          				        = $("#frmSrchContentName").val();
                var Status          						= $("#frmSrchContentStatus").val();

                if (dropdownOpt == '1') { //Activate
                    if(confirm('Do you want to activate this content?')) {
                        GlbSearchParam							    = "rfrom=1&cn="+Content+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+ "&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mcontent/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
                else if (dropdownOpt == '2') { //Deactivate
                    if(confirm('Do you want to Deactivate this content?')) {
                        GlbSearchParam							    = "rfrom=1&cn="+Content+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+ "&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mcontent/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }

            }
        }
        else {
            $('#ErrItemStatus').text("Select a Option");
        }

    });

});