var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
if(lasturlpart == 'managecadrequest') {

}
else if(lasturlpart == 'managemgmtcadrequest') {
    alert(lasturlpart);
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

var GlbfrmCadDeptAcceptReject = '', GlbfrmBasicCadDeptRemarks = '', GlbfrmBasicJobSchedule = '';
function fnSaveCadQueueNo() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        GlbfrmCadDeptAcceptReject     = $("#frmCadDeptAcceptReject").val();
        GlbfrmBasicCadDeptRemarks     = $("#frmBasicCadDeptRemarks").val();
        GlbfrmBasicJobSchedule     = $("#frmBasicJobSchedule").val();
        if(GlbfrmCadDeptAcceptReject != "") {
            $('#myModal').modal('show');
        }
        else {
            $('#ErrfrmBasicErr').text("Please Select Approve or Reject");
            return false;
        }
/*
        if(jsTrim(GlbfrmCadDeptAcceptReject) == "") {
            $('#ErrfrmBasicStyleRefNo').text("Enter Style Ref. No. / Name");
            $('#frmBasicStyleRefNo').focus();
            $('#frmBasicStyleRefNo').css("border", "1px solid #B94A48");
            return false;
        }
*/

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
        MakePostRequest(base_path + GlbCompanyFdr + 'mcadrequest/fnCheckPinForCadQueueNo',"rfrom=1&i="+pw+"&crid="+GlbId+"&s="+GlbfrmCadDeptAcceptReject+"&rem="+
            GlbfrmBasicCadDeptRemarks+"&j="+GlbfrmBasicJobSchedule,'json',fnAuthRes);
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
            $("#saveCadQueuenoAssign").remove();
            if(data.qno != '') {
                $("#cadqueueno").text(data.qno);
                $("#assigneddatetime").text(data.adt);
                //$("#divSuccessBasicInfoMsg").text("APPROVED");
                fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mcadrequest/cadreceivedlist');
            }
            else {
                $("#ErrfrmBasicErr").text('Error');
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

/*$('#frmBasicJobSchedule').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});*/

$("#divLog").hide();
$("#divCompleteLog").hide();
function fnShowCadRequestLog(showdivid,hidedivid) {
    $("#logcircle").removeClass('fa fa-circle-o');
    $("#logcircle").addClass('fa fa-circle');
    $("#basicInfoCircle").removeClass('fa fa-circle');
    $("#basicInfoCircle").addClass('fa fa-circle-o');
    $("#logcompletecircle").removeClass('fa fa-circle');
    $("#logcompletecircle").addClass('fa fa-circle-o');
    $("#"+showdivid).show();
    $("#"+hidedivid).hide();
    $("#divCompleteLog").hide();
    fnCadLogList();
}

function fnShowCadCompleteLog(showdivid,hidedivid,hidethistwo) {
    $("#logcircle").removeClass('fa fa-circle');
    $("#logcircle").addClass('fa fa-circle-o');
    $("#basicInfoCircle").removeClass('fa fa-circle');
    $("#basicInfoCircle").addClass('fa fa-circle-o');
    $("#logcompletecircle").removeClass('fa fa-circle-o');
    $("#logcompletecircle").addClass('fa fa-circle');
    $("#"+showdivid).show();
    $("#"+hidedivid).hide();
    $("#"+hidethistwo).hide();
    fnCadCompleteLogList();
}

function fnCadLogList() {
    MakePostRequest(base_path + GlbCompanyFdr + 'mcadrequest/cadloglist',"rfrom=1"+"&id="+GlbId,'json',fnCadLogListRes);
}

function fnCadLogListRes(data) {
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
                        $.each(data.re,function(index,value) {
                            PageContent=PageContent+'<tr><td>'+value.du+'</td>' +
                                '<td>'+value.cs+'</td>'+
                                '<td>'+value.rem+'</td>'+
                                '<td><a href="'+base_path+GlbCompanyFdr+'mcadrequest/mgmtcadauthorizing/'+encodeURIComponent(base64_encode(value.id))+'">View</a></td>';
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
                $("#cadLogList").append(PageContent);
            }
        }
    }
}

function fnCadCompleteLogList() {
    MakePostRequest(base_path + GlbCompanyFdr + 'mcadrequest/cadCompleteloglist',"rfrom=1"+"&wip="+GlbWipRefNo,'json',fnCadCompleteLogListRes);
}

function fnCadCompleteLogListRes(data) {
    console.log(data);
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
                        $.each(data.re,function(index,value) {
                            PageContent=PageContent+'<tr><td>'+value.du+'</td>' +
                                '<td>'+value.cs+'</td>'+
                                '<td>'+value.rem+'</td>'+
                                '<td><a href="'+base_path+GlbCompanyFdr+'mcadrequest/mgmtcadauthorizing">View</a></td>';
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
                $("#cadCompleteLogList").append(PageContent);
            }
        }
    }
}