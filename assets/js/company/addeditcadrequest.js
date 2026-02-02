var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
if(lasturlpart == 'managecadrequest') {

}
else if(lasturlpart == 'managemgmtcadrequest') {
    //alert(lasturlpart);
}
var GlbSearchParam = '';
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

function fnSearchBom() {
    var frmBasicBomName     					        = $("#frmSrchBomName").val();
    var frmSrchSuppName     					        = $("#frmSrchSuppName").val();
    var Userid     					                    = $("#frmSrchUid").val();
    var PwdExpDate     					                = $("#frmSrchPwdExp").val();
    var Status        							        = $("#frmSrchBomStatus").val();
    GlbFilterAlpha                                      = $('#hiddenAlpha').val();
    GlbSearchParam							            = "rfrom=1&bn="+frmBasicBomName+"&sup="+frmSrchSuppName+"&u="+Userid+"&sn="+frmSrchSuppName+"&ex="+PwdExpDate+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+GlbCompanyFdr+'mcadrequest/managecadrequest',GlbSearchParam,'json',fnListRes);
}

function fnListCadRequest() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+GlbCompanyFdr+'Mcadrequest/managecadrequest',GlbSearchParam,'json',fnListCadRequestRes);
}

function fnListCadRequestRes(data) {
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
                                '<td><a href="'+base_path+GlbCompanyFdr+'mcadrequest/editcadrequest/'+encodeURIComponent(base64_encode(value.id))+'">'+value.wip+'</a></td>' +
                                '<td>'+value.bb+'</td>' +
                                '<td>'+value.styleref+'</td>' +
                                '<td>'+value.r+'</td>' +
                                '<td>'+value.reqtype+'</td>' +
                                '<td>'+value.cutoff+'</td>' +
                                '<td>'+value.m+'</td>' +
                                '<td>'+value.at+'</td>' +
                                '<td>'+value.u+'</td>' +
                                '<td>'+value.s+'</td>'+
                                '<td>'+value.ru+'</td>';
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
                $('#mCadRequestList').append(PageContent);
            }
        }
    }
}

function fnListCadMgmtRequest() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+GlbCompanyFdr+'Mcadrequest/managecadrequest',GlbSearchParam,'json',fnListCadMgmtRequestRes);
}

function fnListCadMgmtRequestRes(data) {
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
                                '<td><a href="'+base_path+GlbCompanyFdr+'mcadrequest/addeditcadrequest/'+encodeURIComponent(base64_encode(value.id))+'">'+value.wip+'</a></td>' +
                                '<td>'+value.bb+'</td>' +
                                '<td>'+value.styleref+'</td>' +
                                '<td>'+value.r+'</td>' +
                                '<td>'+value.reqtype+'</td>' +
                                '<td>'+value.cutoff+'</td>' +
                                '<td>'+value.m+'</td>' +
                                '<td>'+value.at+'</td>' +
                                '<td><a href="'+base_path+GlbCompanyFdr+'mcadrequest/mgmtcadauthorizing/'+encodeURIComponent(base64_encode(value.id))+'">View Details</a></td>' +
                                '<td>'+value.s+'</td>'+
                                '<td>'+value.ru+'</td>';
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
                $('#mgmtCadRequestList').append(PageContent);
            }
        }
    }
}

function fnDelete(Id) {
    if(confirm("Are you want to delete this record?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequest/delInfo',Parameters,'json',fnDeleteRes);
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

function fnSaveCadRequest() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData							= false;
        var frmBasicWipRefNo     					= $("#frmBasicWipRefNo").text();
        var frmBasicWipDate     					= $("#frmBasicWipDate").text();
        var frmBasicStyleRefNo     					= $("#frmBasicStyleRefNo").text();
        var frmBasicPono     					= $("#frmBasicPono").val();
        var frmBasicBB     					    = $("#frmBasicBrandName").text() + $("#frmBasicBuyerName").text();
        var frmBasicCountry     					    = $("#frmBasicCountry").val();
        var frmBasicStyleDesc     					    = $("#frmBasicStyleDesc").text();
        var frmBasicCombo = $("#frmBasicCombo").val();
        var frmBasicCompoment = $("#frmBasicComponent").val();
        var frmBasicColor = $("#frmBasicColor").val();
        var frmBasicSpc = $("#frmBasicSpc").val();
        var frmBasicRequirement = $("#frmBasicRequirement").val();
        var frmBasicPurpose     					    = $("#frmBasicPurpose").val();
        var frmBasicCategory     					    = $("#frmBasicCategory").val();
        var frmBasicCadRefNo     					    = $("#frmBasicCadRefNo").val();
        var frmBasicRequestType     					    = $("#frmBasicRequestType").val();
        var frmBasicRequestSize     					    = $("#frmBasicReqSize").val();
        var frmBasicKnittingType     					    = $("#frmBasicKnittingType").val();
        var frmBasicDyeingType     					    = $("#frmBasicDyeingType").val();
        var frmBasicCompactType     					    = $("#frmBasicCompactType").val();
        var frmBasicCutoffdatetime     					    = $("#frmBasicCutoffdatetime").val();
        //var frmBasicMerchantName     					    = $("#frmBasicMerchantName").val();
        var frmBasicMerchantNote     					    = $("#frmBasicMerchantNote").val();
        if(jsTrim(frmBasicCutoffdatetime)== "") {
            $('#ErrfrmBasicCutoffdatetime').text("Please fill the Cutoff date time");
            $('#frmBasicCutoffdatetime').focus();
            $('#frmBasicCutoffdatetime').css("border", "1px solid #B94A48");
            return false;
        }

/*
        if(jsTrim(frmBasicStyleRefNo) == "") {
            $('#ErrfrmBasicStyleRefNo').text("Please fill the Style Ref No");
            $('#frmBasicStyleRefNo').focus();
            $('#frmBasicStyleRefNo').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(frmBasicStyleDesc) == "") {
            $('#ErrfrmBasicStyleDesc').text("Please fill the Style Description");
            $('#frmBasicStyleDesc').focus();
            $('#frmBasicStyleDesc').css("border", "1px solid #B94A48");
            return false;
        }
*/
        /*if(jsTrim(frmBasicCutoffdatetime) == "") {
            $('#ErrfrmBasicCutoffdateFromTo').text("Please fill the Cutt Off date");
            $('#frmBasicCutoffdateFromTo').focus();
            $('#frmBasicCutoffdateFromTo').css("border", "1px solid #B94A48");
            return false;
        }*/
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("wip",frmBasicWipRefNo);
            ProfileFormData.append("wipdatetime",frmBasicWipDate);
            ProfileFormData.append("sr",frmBasicStyleRefNo);
            ProfileFormData.append("pono",frmBasicPono);
            ProfileFormData.append("bb",frmBasicBB);
            ProfileFormData.append("conty",frmBasicCountry);
            ProfileFormData.append("sd",frmBasicStyleDesc);
            ProfileFormData.append("comboid",frmBasicCombo);
            ProfileFormData.append("componentid",frmBasicCompoment);
            ProfileFormData.append("colorid",frmBasicColor);
            ProfileFormData.append("spc",frmBasicSpc);
            ProfileFormData.append("req",frmBasicRequirement);
            ProfileFormData.append("pur",frmBasicPurpose);
            ProfileFormData.append("cat",frmBasicCategory);
            ProfileFormData.append("cadrefno",frmBasicCadRefNo);
            ProfileFormData.append("reqtype",frmBasicRequestType);
            ProfileFormData.append("reqsize",frmBasicRequestSize);
            ProfileFormData.append("knittype",frmBasicKnittingType);
            ProfileFormData.append("dyetype",frmBasicDyeingType);
            ProfileFormData.append("comtype",frmBasicCompactType);
            ProfileFormData.append("cutoff",frmBasicCutoffdatetime);
            //ProfileFormData.append("merchant",frmBasicMerchantName);
            ProfileFormData.append("mnote",frmBasicMerchantNote);
            ProfileFormData.append("oid",GlbOrderId);
            ProfileFormData.append("id",GlbId);
            ProfileFormData.append("cs",GlbCurrentStatus);
        }
        if (confirm("To confirm click OK, else CANCEL")) {
            $.ajax({
                url 		: base_path+GlbCompanyFdr+'mcadrequest/updateInfo',
                data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                success     : function(data, textStatus, jqXHR){
                    data = JSON.parse(data);
                    fnSaveCadReqRes(data);
                }
            });
        }
        else return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveCadReqRes(data) {
    if(data!='') {
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrfrmBasicBomName').text(data.msg);
            return false;
        } else if(data.errcode==1) {
            GlbId       = data.id;
            extraObj.startUpload();
            //$("#divCurrentStatus").text('CAD Request Sent');
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("CAD Request has been updated successfully!");
            //fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mcadrequest/managecadrequest');
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

    $('#mBomSrcList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);

        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        GlbSearchParam = GlbSearchParam + "&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;
        console.log(GlbSearchParam);
        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequest/managecadrequest',GlbSearchParam,'json',fnListRes);
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
                        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequest/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
                else if (dropdownOpt == '2') { //Deactivate
                    if(confirm('Do you want to Deactivate this bill of material source?')) {
                        GlbSearchParam							    = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequest/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
            }
        }
        else {
            $('#ErrItemStatus').text("Select a Option");
        }
    });

/*
$('#frmBasicCutoffdateFrom').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});
*/


/*$('#frmBasicCutoffdatetime').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});*/

$('#frmSrchReqDateFrom').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

$('#frmSrchReqDateTo').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

function fnPaginationBomSrc(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL,Parameters,'json',fnListRes);
}

$(document).ready(function() {
    extraObj     = $("#uploadcadrequest").uploadFile({
        dragDrop: true,
        multiple:true,
        url:base_path+GlbCompanyFdr+'mcadrequest/fnuploadattachment',
        fileName:"bimage",
        returnType: "json",
        fileName:"myfile",
        dynamicFormData:function () {
            var test = {'cadrequest':GlbId};
            return test;
        },
        autoSubmit:false
    });
    console.log(extraObj,'extraObj');
});

var GlbfrmApproveReject = "", GlbfrmBasicMgmtRemarks = "", GlbfrmCadApprovalType = "";
function fnCadRequestMgmtAuth() {
    try {
        var ProfileFormData = false;
        GlbfrmBasicMgmtRemarks = $("#frmBasicMgmtRemarks").val();
        GlbfrmApproveReject = $("#frmApproveReject").val();
        //var frmBasicMgmtPassword = $("#frmBasicMgmtPassword").val();
        GlbfrmCadApprovalType = $("#frmCadApprovalType").val();
        if (jsTrim(GlbfrmBasicMgmtRemarks) == "") {
            $('#ErrfrmBasicMgmtRemarks').text("Please fill the Management Remarks");
            $('#frmBasicMgmtRemarks').focus();
            $('#frmBasicMgmtRemarks').css("border", "1px solid #B94A48");
            return false;
        }
        if (GlbfrmApproveReject == "") {
            $('#ErrfrmApproveReject').text("Please Approve / Reject");
            $('#frmApproveReject').focus();
            $('#frmApproveReject').css("border", "1px solid #B94A48");
            return false;
        }
        if(GlbfrmApproveReject != "") {
            $('#myModal').modal('show');
        }
        else {
            $('#ErrfrmBasicErr').text("Please Select Approve or Reject");
            return false;
        }
/*
        if (window.FormData) {
            ProfileFormData = new FormData();
            ProfileFormData.append("r", GlbfrmBasicMgmtRemarks);
            ProfileFormData.append("res", GlbfrmApproveReject);
            //ProfileFormData.append("p", frmBasicMgmtPassword);
            ProfileFormData.append("approvaltype", GlbfrmCadApprovalType);
            ProfileFormData.append("id", GlbId);
            $.ajax({
                url: base_path + GlbCompanyFdr + 'mcadrequest/authcadrequestmgmt',
                data: ProfileFormData ? ProfileFormData : ObjForm.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    data = JSON.parse(data);
                    fnCadRequestMgmtAuthRes(data);
                }
            });
            return false;
        }
*/
    }catch(e) {
        alert(e);
    }
}

function fnCheckPin() {
    $(".herr").text('');
    try {
        var pw = $("#frmPin").val();
        if(jsTrim(pw) == "") {
            $("#ErrfrmPin").text('Enter PIN');
            return false;
        }
        MakePostRequest(base_path + GlbCompanyFdr + 'mcadrequest/fnCheckPin',"rfrom=1&pwd="+pw+"&id="+GlbId+"&cs="+GlbfrmApproveReject+"&mgmtremarks="+GlbfrmBasicMgmtRemarks+"&approvaltype="+GlbfrmCadApprovalType,'json',fnAuthRes);fnCadRequestMgmtAuth
        return false;
    } catch (e) {
        alert(e);
    }
}

function fnAuthRes(data) {
    if(data!='') {
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrfrmPin').text(data.msg);
            return false;
        } else if(data.errcode==1) {
            $('#myModal').modal('hide');
            $("#saveCadRequestMgmtAuth").remove();
        }
    }

}

function fnCadRequestMgmtAuthRes(data) {
    if (data != '') {
        if (data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if (data.errcode == -1) {
            $('#ErrfrmCadAuthMsg').text(data.msg);
            return false;
        } else if (data.errcode == 1) {
            GlbId = data.id;

            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("CAD Request Authorized");
            //fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'cadrequest/addedit/'+data.eid);
        }
    }
}