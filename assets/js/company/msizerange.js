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

function fnSearchSizeRange() {
    var SizeName          				        = $("#frmSrchSizeName").val();
    var Status          						= $("#frmSrchSizeStatus").val();
    GlbSearchParam							    = "rfrom=1&sn="+SizeName+"&s="+Status;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'/msizerange/managesizerange',GlbSearchParam,'json',fnListSizeRangeRes);
}

function fnListSizeRange() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'/msizerange/managesizerange',GlbSearchParam,'json',fnListSizeRangeRes);
}

function fnListSizeRangeRes(data){
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
                            PageContent=PageContent+'<tr><td><input type="checkbox" id="'+value.id+'" class="allcbox"></td><td><a href="'+base_path+GlbCompanyFdr+'/msizerange/addedit/'+encodeURIComponent(base64_encode(value.id))+'/">'+value.n+'</a></td><td>'+value.s+'</td><td>'+value.ub+'</td><td>'+value.du+'</td><td><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDeleteSizeRange('+value.id+')">Delete</a></td>';
                            PageContent=PageContent+'</tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                PageContent	= PageContent+'</tbody></table>';
                if(data.pa!=undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $("tbody").empty();
                $("#tableList").append(PageContent);
            }
        }
    }
}

function fnPaginationSizeRange(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListSizeRangeRes);
}

function fnDeleteSizeRange(Id) {
    if(confirm("Are you want to delete this size range?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'/msizerange/delSizeRangeInfo',Parameters,'json',fnDeleteSizeRangeRes);
    }
}

function fnDeleteSizeRangeRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchSizeRange();
            }
        }
    }
}

function fnSaveSizeRangeInfo() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').html('');
        var ProfileFormData							= false;
        var SizeName     							= $("#frmBasicSizeName").val();
        var Status        							= $("#frmBasicStatus").val();
        if(jsTrim(SizeName)== ""){
            $('#ErrBasicSizeName').text("Please fill the size range");
            $('#frmBasicSizeName').focus();
            $('#frmBasicSizeName').css("border", "1px solid #B94A48");
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
            ProfileFormData.append("sn",SizeName);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'/msizerange/updateSizeRangeInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveSizeRangeRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveSizeRangeRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrBasicSizeName').html(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Size Range has updated at successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'/msizerange/addedit/'+data.eid);
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
                fnSearchSizeRange();
            }
        }
    }
}

$( document ).ready(function() {
    $('#mYarnList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);


        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];

        var frmBasicYarnName     					        = $("#frmSrchYarnName").val();
        var Status = $("#frmSrchYarnStatus").val();
        GlbSearchParam							    = "rfrom=1&yn="+frmBasicYarnName+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;
        MakePostRequest(base_path+GlbCompanyFdr+'myarn/manage',GlbSearchParam,'json',fnListRes);

    });


    $('#btnChangeStatus').on('click',function () {
        var dropdownOpt                                 = $('#frmItemStatus').val();
        if(dropdownOpt > 0) {
            var SewTypeIdObject = commonCheckbox();
            var checkBoxLength = SewTypeIdObject[1];
            var cboxObj = SewTypeIdObject[0];
            $('#ErrItemStatus').text("");
            if(checkBoxLength == 0) {
                $('#ErrItemStatus').text("Choose the Size Range");
            }
            if (checkBoxLength >= 1) {
                $('#ErrItemStatus').text("");
                var companyid_json = JSON.stringify(cboxObj);
                var frmBasicYarnName     					        = $("#frmBasicYarnName").val();
                var Status = $("#frmSrchYarnStatus").val();
                if (dropdownOpt == '1') { //Activate
                    if(confirm('Do you want to activate this yarn?')) {
                        GlbSearchParam							    = "rfrom=1&yn="+frmBasicYarnName+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+ "&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'myarn/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
                else if (dropdownOpt == '2') { //Deactivate
                    if(confirm('Do you want to Deactivate this yarn?')) {
                        GlbSearchParam							    = "rfrom=1&yn="+frmBasicYarnName+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+ "&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'myarn/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }

            }
        }
        else {
            $('#ErrItemStatus').text("Select a Option");
        }

    });

});
