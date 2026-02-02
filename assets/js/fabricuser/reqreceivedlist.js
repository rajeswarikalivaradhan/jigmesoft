var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1); var GlbViewdetailsLink = '';
if(lasturlpart == 'managecadrequest') {
    GlbViewdetailsLink = base_path+GlbCompanyFdr+'mcadrequest/editcadrequest/';
}
else if(lasturlpart == 'managemgmtcadrequest') {
    //alert(lasturlpart);
    GlbViewdetailsLink = base_path+GlbCompanyFdr+'mcadrequest/mgmtcadauthorizing/';
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

function fnSearchSamReceivedList() {
    var WipRefNo     					        = $("#frmSrchWipRefNo").val();
    var BB       					            = $("#frmSrchBB").val();
    var CutOffFrom     					        = $("#frmSrchCutOffDateFrom").val();
    var CutOffTo     					        = $("#frmSrchCutOffDateTo").val();
    var ApprType     					        = $("#frmSrchApprovalType").val();
    var Requirement     					    = $("#frmSrchRequirement").val();
    var Merchant     					        = $("#frmSrchMerchantName").val();
    var CStatus     					        = $("#frmSrchCStatus").val();
    var isriortype     					        = $("#frmNameSearchIsrIorType").val();
    GlbFilterAlpha                              = $('#hiddenAlpha').val();
    GlbSearchParam				= "rfrom=1&wip="+WipRefNo+"&isriortype="+isriortype+"&cutfrom="+CutOffFrom+"&cutto="+CutOffTo+"&req="+Requirement+"&cs="+CStatus+"&afilter="+
        GlbFilterAlpha+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+"&mer="+Merchant+"&bb="+BB+"&apprtype="+ApprType;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+'fabricuser/fabricreceivedlist',GlbSearchParam,'json',fnRecdListRes);
}

function fnRecdList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+'fabricuser/fabricreceivedlist',GlbSearchParam,'json',fnRecdListRes);
}

function fnRecdListRes(data) {
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
                                '<td><a href="'+base_path+'samplinguser/queuenoassign/'+encodeURIComponent(base64_encode(value.id))+'"> '+value.wip+'</a></td>' +
                                '<td>'+value.bb+'</td>' +
                                '<td>'+value.r+'</td>' +
                                '<td>'+value.reqtype+'</td>' +
                                '<td>'+value.reqdt+'</td>' +
                                '<td>'+value.cutoff+'</td>' +
                                '<td>'+value.at+'</td>' +
                                '<td>'+value.authby+'</td>' +
                                '<td>'+value.m+'</td>' +
                                '<td><a href="javascript:void(0)">'+value.cads+'</a></td>' +
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
                $('#SamReceivedListTbl').append(PageContent);
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
                fnRecdList();
            }
        }
    }
}

    $('#SamReceivedListTbl').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        var Param = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        console.log(Param,'Param');
        MakePostRequest(base_path+'fabricuser/fabricreceivedlist',Param,'json',fnRecdListRes);
    });


$('#btnChangeStatus').on('click',function () {
    var StatusOptSelVal                         = $('#frmItemStatus').val();
    if(parseInt(StatusOptSelVal) > 0) {
        var ArrItemCheckBoxSel                  = commonCheckbox();
        var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
        $('#ErrItemStatus').html("");
        if(parseInt(ArrItemCheckBoxSel[1]) == 0) {$('#ErrItemStatus').html("Select the Checkbox");}
        if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
            $('#ErrItemStatus').html("");
            var StatusText                      = "Deactivate";
            if(StatusOptSelVal == '1') {
                var StatusText                  = "Activate";
            }
            if(confirm('Do you want to '+StatusText+' this records?')) {
                MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal + "&id=" + JSON.stringify(ObjChkSelVal)+"&tblname=kn_cad_request",'json',fnChangeStatusRes);
            }
        }
    } else {
        $('#ErrItemStatus').html("Select a Option");
    }
});


$('#frmSrchCutOffDateFrom').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});


$('#frmSrchCutOffDateTo').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

function fnPaginationReqreceived(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL,Parameters,'json',fnRecdListRes);
}