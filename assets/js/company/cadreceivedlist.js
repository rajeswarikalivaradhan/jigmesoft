var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1); var GlbViewdetailsLink = '';
if(lasturlpart == 'managecadrequest') {
    GlbViewdetailsLink = base_path+GlbCompanyFdr+'mcadrequest/editcadrequest/';
}
else if(lasturlpart == 'managemgmtcadrequest') {
    //alert(lasturlpart);
    GlbViewdetailsLink = base_path+GlbCompanyFdr+'mcadrequest/mgmtcadauthorizing/';
}
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

function fnSearchCadReceivedList() {
    var WipRefNo     					        = $("#frmSrchWipRefNo").val();
    //var StyleRefNo     					        = $("#frmSrchStyleRefNo").val();
    var BB       					            = $("#frmSrchBB").val();
    var CutOffFrom     					        = $("#frmSrchCutOffDateFrom").val();
    var CutOffTo     					        = $("#frmSrchCutOffDateTo").val();
    var ReqDateFrom     					    = $("#frmSrchReqDateFrom").val();
    var ReqDateTo     					        = $("#frmSrchReqDateTo").val();
    var ReqType     					        = $("#frmSrchReqType").val();
    var Requirement     					    = $("#frmSrchRequirement").val();
    var Merchant     					        = $("#frmSrchMerchantName").val();
    var CStatus     					        = $("#frmSrchCStatus").val();
    GlbFilterAlpha                              = $('#hiddenAlpha').val();
    GlbSearchParam				= "rfrom=1&wip="+WipRefNo+"&cutfrom="+CutOffFrom+"&cutto="+CutOffTo+"&rf="+ReqDateFrom+"&rt="+ReqDateTo+"&req="+Requirement+"&cs="+CStatus+"&afilter="+
        GlbFilterAlpha+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+"&reqtype="+ReqType+"&mer="+Merchant+"&bb="+BB;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+GlbCompanyFdr+'mcadrequest/cadreceivedlist',GlbSearchParam,'json',fnListCadRequestRes);
}

function fnListCadRecdList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+'caduser/cadreceivedlist',GlbSearchParam,'json',fnListCadRequestRes);
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
                                //'<td><a href="'+base_path+GlbCompanyFdr+'mcadrequest/editcadrequest/'+encodeURIComponent(base64_encode(value.id))+'">'+value.wip+'</a></td>' +
                                '<td>'+value.wip+'</td>' +
                                '<td>'+value.bb+'</td>' +
                                '<td>'+value.styleref+'</td>' +
                                '<td>'+value.r+'</td>' +
                                '<td>'+value.reqtype+'</td>' +
                                '<td>'+value.cutoff+'</td>' +
                                '<td>'+value.m+'</td>' +
                                '<td>'+value.at+'</td>' +
                                '<td><a href="'+base_path+'caduser/cadqueuenoassign/'+encodeURIComponent(base64_encode(value.id))+'">View Details</a> </td>' +
                                '<td>'+value.cads+'</td>' +
                                '<td>'+value.ru+'</td>'+
                                '<td>'+value.s+'</td>';
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
                $('#mCadReceivedList').append(PageContent);
            }
        }
    }
}

function fnListCadMgmtRequest() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+GlbCompanyFdr+'Mcadrequest/managecadrequest',GlbSearchParam,'json',fnListCadMgmtRequestRes);
}

/*

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
                                '<td>'+value.cads+'</td>'+
                                '<td>'+value.ru+'</td>'+
                                '<td>'+value.s+'</td>';
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

*/

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
                fnSearchCadReceivedList();
            }
        }
    }
}

    $('#mBomSrcList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        console.log(GlbSearchParam);
        MakePostRequest(base_path+GlbCompanyFdr+'mcadrequest/managecadrequest',GlbSearchParam,'json',fnListRes);
    });


$('#btnChangeStatus').on('click',function () {
    var StatusOptSelVal                         = $('#frmItemStatus').val();
    if(parseInt(StatusOptSelVal) > 0) {
        var ArrItemCheckBoxSel                  = commonCheckbox();
        var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
        $('#ErrItemStatus').text("");
        if (parseInt(ArrItemCheckBoxSel[1]) == 0) {
            $('#ErrItemStatus').text("Select the Checkbox");
        }
        if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
            $('#ErrItemStatus').text("");
            var StatusText                      = "Deactivate";
            if(StatusOptSelVal == '1') {
                var StatusText                  = "Activate";
            }
            if(confirm('Do you want to '+StatusText+' this records?')) {
                MakeAsynPostRequest(base_path+GlbCompanyFdr+'mcadrequest/changeActiveInactiveStatus',"cs=" + StatusOptSelVal + "&id=" + JSON.stringify(ObjChkSelVal),'json',fnChangeStatusRes);
            }
        }
    } else {
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


$('#frmBasicCutoffdatetime').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

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