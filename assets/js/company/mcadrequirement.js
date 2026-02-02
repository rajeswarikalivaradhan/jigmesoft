var GlbSearchParam = '';
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

function fnSearchCadRequirement() {
    var frmSrchCadRequirement     					        = $("#frmSrchCadRequirement").val();
    var Status        							        = $("#frmSrchStatus").val();
    GlbFilterAlpha                                      = $('#hiddenAlpha').val();
    GlbSearchParam = "rfrom=1&r=" + frmSrchCadRequirement + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+GlbCompanyFdr+'mcadrequirement/managecadrequirement',GlbSearchParam,'json',fnListRes);
}

function fnListCadRequirements() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakePostRequest(base_path+GlbCompanyFdr+'mcadrequirement/managecadrequirement',GlbSearchParam,'json',fnListRes);
}

function fnListRes(data){
    console.log(data); console.log(data.re);
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
                                '<td><a href="'+base_path+GlbCompanyFdr+'mcadrequirement/addeditcadrequirement/'+encodeURIComponent(base64_encode(value.id))+'">'+value.req+'</a></td>' +
                                '<td>'+value.s+'</td>' +'<td>'+value.u+'</td><td>'+value.du+'</td>' +
                                '<td><i class="fa fa-trash-o"></i><a href="javascript:void(0);" onclick="fnCadRequirementDelete('+value.id+')">Delete</a></td>';
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
                $('#mCadRequirementList').append(PageContent);
            }
        }
    }
}

function fnCadRequirementDelete(Id) {
    if(confirm("Are you want to delete this requirement?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequirement/delInfo',Parameters,'json',fnDeleteRes);
    }
}

function fnDeleteRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchBom();
            }
        }
    }
}

function fnSaveCadRequirementInfo() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData							= false;
        var CadRequirement     					= $("#frmBasicCadRequirement").val();
        var Status        							= $("#frmBasicStatus").val();
        if(jsTrim(CadRequirement)== ""){
            $('#ErrfrmBasicCadRequirement').text("Please fill the requirement");
            $('#frmBasicCadRequirement').focus();
            $('#frmBasicCadRequirement').css("border", "1px solid #B94A48");
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
            ProfileFormData.append("r",CadRequirement);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'mcadrequirement/updatecadrequirementinfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveCadRequirementRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveCadRequirementRes(data) {

    if(data!='') {
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrfrmBasicCadRequirement').text(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("CAD Requirement has been updated successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mcadrequirement/addeditcadrequirement/'+data.eid);
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
                fnSearchBom();
            }
        }
    }
}

    $('#mCadReqList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
console.log(GlbSearchParam);
        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequirement/managecadrequirement',GlbSearchParam,'json',fnListRes);

    });



    $('#btnChangeStatus').on('click',function () {
        var dropdownOpt                                 = $('#frmItemStatus').val();
        if(dropdownOpt > 0) {
            var SewTypeIdObject = commonCheckbox();
            var checkBoxLength = SewTypeIdObject[1];
            var cboxObj = SewTypeIdObject[0];
            $('#ErrItemStatus').text("");
            if(checkBoxLength == 0) {
                $('#ErrItemStatus').text("Choose a bill of material source");
            }
            if (checkBoxLength >= 1) {
                $('#ErrItemStatus').text("");
                var companyid_json = JSON.stringify(cboxObj);
                if (dropdownOpt == '1') { //Activate
                    if(confirm('Do you want to activate this bill of material source?')) {
                        GlbSearchParam							    = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequirement/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
                else if (dropdownOpt == '2') { //Deactivate
                    if(confirm('Do you want to Deactivate this bill of material source?')) {
                        GlbSearchParam							    = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequirement/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
            }
        }
        else {
            $('#ErrItemStatus').text("Select a Option");
        }
    });

function fnPaginationReqirement(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL,Parameters,'json',fnListRes);
}

/**************************************** PURPOSE ****************************************/
function fnSearchPurpose() {
    var frmSrchCadPurpose     					        = $("#frmSrchCadPurpose").val();
    var Status        							        = $("#frmSrchStatus").val();
    GlbFilterAlpha                                      = $('#hiddenAlpha').val();
    GlbSearchParam = "rfrom=1&p=" + frmSrchCadPurpose + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+GlbCompanyFdr+'mcadrequirement/managecadpurpose',GlbSearchParam,'json',fnListPurpose);
}

function fnListPurpose() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakePostRequest(base_path+GlbCompanyFdr+'mcadrequirement/managecadpurpose',GlbSearchParam,'json',fnListPurposeRes);
}

function fnListPurposeRes(data){
    console.log(data); console.log(data.re);
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
                                '<td><a href="'+base_path+GlbCompanyFdr+'mcadrequirement/addeditcadpurpose/'+encodeURIComponent(base64_encode(value.id))+'">'+value.p+'</a></td>' +
                                '<td>'+value.s+'</td>' +'<td>'+value.u+'</td><td>'+value.du+'</td>' +
                                '<td><i class="fa fa-trash-o"></i><a href="javascript:void(0);" onclick="fnCadPurposeDelete('+value.id+')">Delete</a></td>';
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
                $('#mCadPurposeList').append(PageContent);
            }
        }
    }
}

function fnSaveCadPurposeInfo() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData							= false;
        var CadPurpose     					    = $("#frmBasicCadPurpose").val();
        var Status        							= $("#frmBasicStatus").val();
        if(jsTrim(CadPurpose)== ""){
            $('#ErrfrmBasicCadPurpose').text("Please fill the purpose");
            $('#frmBasicCadPurpose').focus();
            $('#frmBasicCadPurpose').css("border", "1px solid #B94A48");
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
            ProfileFormData.append("p",CadPurpose);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'mcadrequirement/updatecadpurposeinfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveCadPurposeRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveCadPurposeRes(data) {
    if(data!='') {
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrfrmBasicCadPurpose').text(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Purpose has been updated successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mcadrequirement/addeditcadpurpose/'+data.eid);
        }
    }
}

function fnCadPurposeDelete(Id) {
    if(confirm("Are you want to delete this purpose?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequirement/delcadpurposeinfo',Parameters,'json',fnCadPurposeDeleteRes);
    }
}

function fnCadPurposeDeleteRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchBom();
            }
        }
    }
}
