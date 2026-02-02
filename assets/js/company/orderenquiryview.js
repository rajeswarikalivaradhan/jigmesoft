var GlbAuthStatus = ""; var GlbRefType = ""; var GlbPcsSet = ''; var GlbComments = '';
var obj = document.getElementById('frmPin');
obj.addEventListener("keydown", stopCarret);
obj.addEventListener("keyup", stopCarret);
function stopCarret() {
    if (obj.value.length > 3){
        setCaretPosition(obj, 3);
    }
}
function setCaretPosition(elem, caretPos) {
    if(elem != null) {
        if(elem.createTextRange) {
            var range = elem.createTextRange();
            range.move('character', caretPos);
            range.select();
        }
        else {
            if(elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(caretPos, caretPos);
            }
            else
                elem.focus();
        }
    }
}
function fnSaveEnquiryApproval() {
    $(".herr").text('');
    $(".form-control").css("border", "1px solid #cccccc");
    try {
        //
        var OrderEnqRefNo     = $("#frmOrderEnqRefNo").val();
        var StyDesc     = $("#frmBasicStyleDesc").val();
        var Styref = $("#frmBasicStyleRefNo").val();
        var frmBasicMnote = $("#frmBasicMnote").val();
        var EnquiryDate = $("#frmBasicEnqDate").val();
        var frmBasicEType = $("#frmBasicEType").val();
        var frmBasicMoE = $("#frmBasicMoE").val();
        var frmBasicBB = $("#frmBasicBB").val();
        var frmBasicPs = $("#frmBasicPs").val();
        var frmBasicCountry = $("#frmBasicCountry").val();
        var frmBasicQprice = $("#frmBasicQprice").val();
        var frmBasicBprice = $("#frmBasicBprice").val();
        var frmBasicCprice = $("#frmBasicCprice").val();
        var frmBasicCurrency = $("#frmBasicCurrency").val();
        var frmBasicPqty = $("#frmBasicPqty").val();
        var frmBasicRType = $("#frmBasicRType").val();
        var frmBasicISRany = $("#frmBasicISRany").val();
        var PriceQuotedFor = $("#frmPriceQuotedFor").val();
        //
        GlbComments = $("#frmBasicComments").val();
        GlbAuthStatus = $("#frmBasicOrderStatus").val();
        if(jsTrim(GlbComments) == "" && GlbAuthStatus == "3") {
            $('#ErrfrmBasicComments').text("Enter Comments");
            $('#frmBasicComments').focus();
            $('#frmBasicComments').css("border", "1px solid #ff0000");
            return false;
        }
        if(GlbAuthStatus != "") {
            $('#myModal').modal('show');
        }
        else {
            $('#ErrfrmBasicErr').text("Please Select Approve or Reject");
            return false;
        }
    } catch (e) {
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
        MakeAsynPostRequest(base_path + 'management/fnCheckPin',"rfrom=1&i="+pw+"&enqid="+GlbEnquiryId+"&s="+
            GlbAuthStatus+"&c="+encodeURIComponent(GlbComments)+"&ty="+GlbIsrIor,'json',fnAuthRes);
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
        } else if(data.errcode=='-1') {
            $('#ErrfrmPin').text(data.msg);
            return false;
        } else if(data.errcode=='1') {
            $('#myModal').modal('hide');
            if(data.assignedno != 0) {
                $("#divSuccessBasicInfoDiv").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("APPROVED");
                fnRedirectPageTimeOut(base_path+'management/manageWip');
            }
            else {
                $("#divSuccessBasicInfoDiv").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text('REJECTED');
                fnRedirectPageTimeOut(base_path+'management/orderEnquiryList');
            }
        }
    }
}
$("#divLog").hide(); $("#logdetaildiv").hide(); var GlbSearchParam = '';
function fnShowEnqInfo() {
    $("#divBasicInfo").show();
    $("#basicInfoCircle").removeClass('fa fa-circle-o');
    $("#basicInfoCircle").addClass('fa fa-circle');
    $("#logcircle").removeClass('fa fa-circle');
    $("#logcircle").addClass('fa fa-circle-o');
    $("#divLog").hide();
    $("#logdetaildiv").hide();
    $("#editopt").text(' - Edit Option');
}
function fnShowEnqLog(showdivid,hidedivid) {
    $("#logcircle").removeClass('fa fa-circle-o');
    $("#logcircle").addClass('fa fa-circle');
    $("#basicInfoCircle").removeClass('fa fa-circle');
    $("#basicInfoCircle").addClass('fa fa-circle-o');
    $("#"+showdivid).show();
    $("#"+hidedivid).hide();
    $("#logdetaildiv").hide();
    MakePostRequest(base_path + GlbCompanyFdr + 'menquiry/fnLogList',"rfrom=1&enquiryid="+GlbEnquiryId,'json',fnListEnqLogRes);
}
function fnListEnqLogRes(data) {
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
                            PageContent=PageContent+'<tr><td>'+value.sd+'</td>' +
                                '<td>'+value.as+'</td>'+
                                '<td>'+value.comm+'</td>'+
                                '<td><a href="javascript:void(0);" onclick="fnEnqDetail(this)" id="'+value.id+'" onclick="">View</a></td>';
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
                $("tbody").empty();
                $("#tableLogList").append(PageContent);
            }
        }
    }
}

function fnPaginationEnLogList(VarURL) {
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL,"rfrom=1&enquiryid="+GlbEnquiryId,'json',fnListEnqLogRes);
}
var GlbLogId = '';
function fnEnqDetail(thisobj) {
    $("#divBasicInfo").hide();
    $("#divLog").hide();
    $("#logdetaildiv").show();
    $("#logcircle").removeClass('fa fa-circle');
    $("#logcircle").addClass('fa fa-circle-o');
    GlbLogId = thisobj.id;
    MakePostRequest(base_path+'management/enquirylogdetail',"rfrom=1&lid="+GlbLogId,'json',fnEnqLogDetailRes);
}

function fnEnqLogDetailRes(detaildata) {
    //console.log(detaildata,detaildata);
    $("#lgstyleref").text(detaildata.logdetaildata.styleref);
    $("#lgstyledesc").text(detaildata.logdetaildata.styledesc);
    $("#lgmcomm").text(detaildata.logdetaildata.mcomm);
    $("#lgenqdt").text(detaildata.logdetaildata.enqdt);
    $("#lgenqtype").text(detaildata.logdetaildata.enqtype);
    $("#lgme").text(detaildata.logdetaildata.enqmode);
    $("#lgbb").text(detaildata.logdetaildata.bb);
    $("#lgconty").text(detaildata.logdetaildata.conty);
    $("#lgisrior").text(detaildata.logdetaildata.isrior);
    $("#lgps").text(detaildata.logdetaildata.pcsset);
    $("#lgqp").text(detaildata.logdetaildata.qp);
    $("#lgbp").text(detaildata.logdetaildata.bp);
    $("#lgcp").text(detaildata.logdetaildata.cp);
    $("#lgpqty").text(detaildata.logdetaildata.pqty);
    $("#lgcurrency").text(detaildata.logdetaildata.currency);
    $("#lgmnote").text(detaildata.logdetaildata.mnote);
    $("#lgmerc").text(detaildata.logdetaildata.mname);
    $("#lgreqdt").text(detaildata.logdetaildata.lgreqdt);
    $("#lgmgmtreupdate").text(detaildata.logdetaildata.lgreupdate);
    if(detaildata.logdetaildata.orstatus == '1') {
        $("#divLogDetailPendingCs").addClass('alert alert-warning alert-dismissable');
        $("#divLogDetailPendingCs").text(detaildata.logdetaildata.VarCurrentStatus);
    }
    if(detaildata.logdetaildata.orstatus == '2') {
        $("#divLogDetailApprovedCs").addClass('alert alert-success alert-dismissable');
        $("#divLogDetailApprovedCs").text(detaildata.logdetaildata.VarCurrentStatus);
    }
    if(detaildata.logdetaildata.orstatus == '3') {
        $("#divLogDetailRejectCs").addClass('alert alert-danger alert-dismissable');
        $("#divLogDetailRejectCs").text(detaildata.logdetaildata.VarCurrentStatus);
    }
    if(detaildata.logdetaildata.orstatus == '4') {
        $("#divLogDetailPendingRRCs").addClass('alert alert-warning alert-dismissable');
        $("#divLogDetailPendingRRCs").text(detaildata.logdetaildata.VarCurrentStatus);
    }


    var ele = 'No attachemts';
    if(detaildata.downloads.length >= 1) {
        ele = '';
        for (var i = 0; i < detaildata.downloads.length; i++) {

            ele += '<li>' + detaildata.downloads[i].fn + '&nbsp;';

            ele += '<a href="' + base_path + GlbCompanyFdr + 'menquiry/download?enqid=' + detaildata.downloads[i].id + '' +
                '&filename=' + detaildata.downloads[i].fn + '"><i class="fa fa-download fa-lg" aria-hidden="true"></i></a>&nbsp;&nbsp;';

            ele += '<a href="' + base_path + 'uploads/orderenquiry/' + detaildata.downloads[i].id + '/' + detaildata.downloads[i].fn + '" target="_blank">' +
                '<i class="fa fa-file fa-lg" aria-hidden="true"></i></a></li>'
        }
    }
    $("#downloads").html(ele);
}

$(function () {
    $("#frmPin").keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            fnCheckPin();
            return false;
        }
    });
});