var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);

if(lasturlpart == 'managecadrequest') {

}
else if(lasturlpart == 'managemgmtcadrequest') {

}
var GlbSearchParam = '';
var GlbFilterAlpha=''; var GlbSortOrder=''; var GlbColumnId='';
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
function fnSaveCadRequest() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData							= false;
        var frmBasicPono     					= $("#frmBasicPono").val();
        var frmBasicCombo = $("#frmBasicCombo").val();
        var frmBasicCompoment = $("#frmBasicComponent").val();
        var frmBasicColor = $("#frmBasicColor").val();
        var frmBasicSpc = $("#frmBasicSpc").val();
        var frmBasicRequirement = $("#frmBasicRequirement").val();
        var frmBasicPurpose     					    = $("#frmBasicPurpose").val();
        var frmBasicCategory     					    = $("#frmBasicCategory").val();
        var frmBasicCadRefNo     					    = $("#frmBasicCadRefNo").val();
        var frmBasicPrevCadRefNo     					    = $("#frmBasicPrevCadRefNo").val();
        var frmBasicRequestType     					    = $("#frmBasicRequestType").val();
        var frmBasicRequestSize     					    = $("#frmBasicReqSize").val();
        var frmBasicKnittingType     					    = $("#frmBasicKnittingType").val();
        var frmBasicDyeingType     					    = $("#frmBasicDyeingType").val();
        var frmBasicCompactType     					    = $("#frmBasicCompactType").val();
        var frmBasicCutoffdatetime     					    = $("#frmBasicCutoffdatetime").val();
        var frmBasicMerchantNote     					    = $("#frmBasicMerchantNote").val();
        var frmBuyersOriginalSample     					    = $("#frmBuyersOriginalSample").val();
        var frmBuyersComments     					    = $("#frmBuyersComments").val();
        var frmAppGradMeasChartDd     					    = $("#frmAppGradMeasChartDd").val();
        var frmCompleteArtwork     					    = $("#frmCompleteArtwork").val();
        var frmMeasureDetailsArtwork     					    = $("#frmMeasureDetailsArtwork").val();
        if(jsTrim(frmBasicCutoffdatetime)== "") {
            $('#ErrfrmBasicCutoffdatetime').html("Please fill the Cutoff date time");
            $('#frmBasicCutoffdatetime').focus();
            $('#frmBasicCutoffdatetime').css("border", "1px solid #B94A48");
            return false;
        }
        console.log(jxldata,'jxldata');
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("pono",frmBasicPono);
            ProfileFormData.append("comboid",frmBasicCombo);
            ProfileFormData.append("componentid",frmBasicCompoment);
            ProfileFormData.append("colorid",frmBasicColor);
            ProfileFormData.append("spc",frmBasicSpc);
            ProfileFormData.append("req",frmBasicRequirement);
            ProfileFormData.append("pur",frmBasicPurpose);
            ProfileFormData.append("cat",frmBasicCategory);
            ProfileFormData.append("cadrefno",frmBasicCadRefNo);
            ProfileFormData.append("prevcadrefno",frmBasicPrevCadRefNo);
            ProfileFormData.append("reqtype",frmBasicRequestType);
            ProfileFormData.append("reqsize",frmBasicRequestSize);
            ProfileFormData.append("knittype",frmBasicKnittingType);
            ProfileFormData.append("dyetype",frmBasicDyeingType);
            ProfileFormData.append("comtype",frmBasicCompactType);
            ProfileFormData.append("cutoff",frmBasicCutoffdatetime);
            ProfileFormData.append("mnote",frmBasicMerchantNote);
            ProfileFormData.append("oid",GlbOrderId);
            ProfileFormData.append("id",GlbId);
            ProfileFormData.append("cs",GlbMgmtCurrentStatus);
            ProfileFormData.append("frmBuyersOriginalSample",frmBuyersOriginalSample);
            ProfileFormData.append("frmBuyersComments",frmBuyersComments);
            ProfileFormData.append("frmAppGradMeasChartDd",frmAppGradMeasChartDd);
            ProfileFormData.append("frmCompleteArtwork",frmCompleteArtwork);
            ProfileFormData.append("frmMeasureDetailsArtwork",frmMeasureDetailsArtwork);
        }
        if (confirm("To confirm click OK, else CANCEL")) {
            $.ajax({
                url 		: base_path+'merchant/updateCadRequestInfo',
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
        } else if(data.errcode=='-1'){
            $('#ErrfrmBasicBomName').text(data.msg);
            return false;
        } else if(data.errcode==1) {
            GlbId       = data.id;
            console.log(GlbId,'GlbId');
            extraObj.startUpload();
            //$("#divCurrentStatus").text('CAD Request Sent');
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("CAD Request has been updated successfully!");
            //fnRedirectPageTimeOut(base_path+'merchant/manageallrequest');
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
        url:base_path+'merchant/fnUploadCadRequest',
        fileName:"bimage",
        returnType: "json",
        fileName:"myfile",
        dynamicFormData:function () {
            var test = {'cadrequestid':GlbId};
            return test;
        },
        autoSubmit:false
    });
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
            $('#ErrfrmBasicMgmtRemarks').html("Please fill the Management Remarks");
            $('#frmBasicMgmtRemarks').focus();
            $('#frmBasicMgmtRemarks').css("border", "1px solid #B94A48");
            return false;
        }
        if (GlbfrmApproveReject == "") {
            $('#ErrfrmApproveReject').html("Please Approve / Reject");
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