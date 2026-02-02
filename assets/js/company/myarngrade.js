var GlbSearchParam='';
var GlbSortOrder = '';
var GlbColumnId = '';
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

function fnSearch() {
    var frmBasicYarnName     					        = $("#frmSrchYarnName").val();
    var Status        							= $("#frmSrchYarnStatus").val();
    GlbSearchParam = "rfrom=1&yn=" + frmBasicYarnName + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'myarn/manageyarngrade',GlbSearchParam,'json',fnListRes);
}

function fnListYarn() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'myarn/manageyarngrade',GlbSearchParam,'json',fnListRes);
}

function fnListRes(data){
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
                            PageContent=PageContent+'<tr><td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>' +
                                '<td><a href="'+base_path+GlbCompanyFdr+'myarn/addedityarngrade/'+encodeURIComponent(base64_encode(value.id))+'/">'+value.yg+'</a></td>' +
                                '<td>'+value.s+'</td><td>'+value.ub+'</td><td>'+value.du+'</td><td><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDelete('+value.id+')">Delete</a></td>';
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
                $('#mYarnGradeList').append(PageContent);
            }
        }
    }
}

function fnPaginationYarnGrade(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListRes);
}

function fnDelete(Id) {
    if(confirm("Are you want to delete this yarn purchase type?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'myarn/delyarnpurchaseinfo',Parameters,'json',fnDeleteRes);
    }
}

function fnDeleteRes(data) {
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearch();
            }
        }
    }
}

function fnSaveYarnGrade() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData							= false;
        var frmBasicYarngrade     			= $("#frmBasicYarnGrade").val();
        var Status        							= $("#frmBasicStatus").val();
        if(jsTrim(frmBasicYarngrade) == ""){
            $('#ErrfrmBasicYarnPurchaseType').text("Please fill the yarn grade");
            $('#frmBasicYarnGrade').focus();
            $('#frmBasicYarnGrade').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(Status) == ""){
            $('#ErrBasicStatus').text("Please select the status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("yg",frmBasicYarngrade);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        //console.log(ProfileFormData);
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'myarn/updateyarngradeinfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveYarnRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveYarnRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrfrmBasicYarnName').text(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Yarn Grade has updated at successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'myarn/addedityarngrade/'+data.eid);
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
                fnSearch();
            }
        }
    }
}

    $('#mYarnGradeList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];

        var frmBasicYarnName     					        = $("#frmSrchYarnName").val();
        var Status = $("#frmSrchYarnStatus").val();
        GlbSearchParam = "rfrom=1&yn=" + frmBasicYarnName + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        MakePostRequest(base_path+GlbCompanyFdr+'myarn/manageyarngrade',GlbSearchParam,'json',fnListRes);

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
                        GlbSearchParam = "rfrom=1&yn=" + frmBasicYarnName + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder + "&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'myarn/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
                else if (dropdownOpt == '2') { //Deactivate
                    if(confirm('Do you want to Deactivate this yarn?')) {
                        GlbSearchParam = "rfrom=1&yn=" + frmBasicYarnName + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder + "&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'myarn/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }

            }
        }
        else {
            $('#ErrItemStatus').text("Select a Option");
        }

    });