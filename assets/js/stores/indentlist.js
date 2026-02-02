var GlbSearchParam = '', GlbFilterAlpha='', GlbSortOrder='', GlbColumnId='';
/*var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1); var GlbViewdetailsLink = '';
if(lasturlpart == 'managecadrequest') {
    GlbViewdetailsLink = base_path+GlbCompanyFdr+'mcadrequest/editcadrequest/';
    function fnListCadRequest() {
        $("#DivTotalCntResult").html('');
        GlbSearchParam = 'rfrom=1';
        MakeAsynPostRequest(base_path+GlbCompanyFdr+'mcadrequest/managecadrequest',GlbSearchParam,'json',fnListCadRequestRes);
    }

}
else if(lasturlpart == 'managemgmtcadrequest') {

}*/

//GlbViewdetailsLink = base_path+GlbCompanyFdr+'mcadrequest/mgmtcadauthorizing/';
function fnListIndent() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+'storesuser/indentlist',GlbSearchParam,'json',fnListIndentRes);
}
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

function fnSearchIndent() {
    var WipRefNo     					        = $("#frmSrchWipRefNo").val();
    var IsrIorType     					                    = $("#frmNameSearchIsrIorType").val();
    var BB       					        = $("#frmSrchBB").val();
    //var AllReq     					                = $("#frmSrchAllReq").val();
    var SrchQno     					                = $("#frmSrchQno").val();
    var CutOffFrom     					                    = $("#frmSrchCutOffDateFrom").val();
    var CutOffTo     					                    = $("#frmSrchCutOffDateTo").val();
    var approvaltype     					                = $("#frmSrchapprovaltypeType").val();
    var SrchIndTo = ''; var SrchIndFrom = '';
    if(GlbUserType == 3 || GlbUserType == 4 || GlbUserType == 15) { // Note GlbUserType == 15 included in this files at GlbUserType == 3 places
        SrchIndTo					                    = $("#frmSrchIndTo").val();
    }
    else if(GlbUserType == 5) {
        SrchIndFrom     					                = $("#frmSrchIndFrom").val();
    }
    var CStatus     					                = $("#frmSrchCStatus").val();

    GlbFilterAlpha                                      = $('#hiddenAlpha').val();
    GlbSearchParam				= "rfrom=1&wip="+WipRefNo+"&bb="+BB+"&cutfrom="+CutOffFrom+"&cutto="+CutOffTo+"&IsrIorType="+IsrIorType+"&SrchQno="+SrchQno+
        "&cs=" + CStatus + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder + "&at=" + approvaltype + "&SrchIndFrom=" + SrchIndFrom + "&SrchIndTo=" + SrchIndTo;

    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+'storesuser/indentlist',GlbSearchParam,'json',fnListIndentRes);
}

function fnListIndentRes(data) {
    if(data!='') {
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
                                PageContent = PageContent + '<tr><td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                    /*'<td><a href="'+base_path+'merchant/editcadrequest/'+encodeURIComponent(base64_encode(value.id))+'">'+value.wip+'</a></td>' +*/
                                    '<td>'+value.wip+'</td>' +
                                    '<td>' + value.bb + '</td>' +
                                    '<td>' + value.qno + '</td>' +
                                    //'<td><a href="' + base_path + 'dashboard/inddetailsprintformat/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.indrefno + '</a></td>' +
                                    '<td><a href="' + base_path + 'storesuser/indentdetails/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.indrefno + '</a></td>' +
                                    '<td>' + value.requestdt + '</td>' +
                                    '<td>' + value.cutoff + '</td>' +
                                    '<td>' + value.at + '</td>' +
                                    '<td>' + value.authby + '</td>' +
                                    '<td><a href="javascript:void(0)">' + value.cads + '</a></td>' +
                                    '<td>' + value.ru + '</td>' +
                                    '<td>' + value.s + '</td>';
                                PageContent = PageContent + '</tr>';
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
                $('#mCadIndentList').append(PageContent);
            }
        }
    }
}

/*function fnListCadMgmtRequestRes(data) {
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
                                '<td><a href="'+base_path+GlbCompanyFdr+'mcadrequest/editcadrequest/'+encodeURIComponent(base64_encode(value.id))+'">'+value.wip+'</a></td>' +
                                '<td>'+value.bb+'</td>' +
                                '<td>'+value.r+'</td>' +
                                '<td>'+value.reqtype+'</td>' +
                                '<td>'+value.reqdt+'</td>' +
                                '<td>'+value.cutoffdt+'</td>' +
                                '<td>'+value.m+'</td>' +
                                '<td>'+value.at+'</td>' +
                                '<td><a href="'+base_path+GlbCompanyFdr+'mcadrequest/mgmtcadauthorizing/'+encodeURIComponent(base64_encode(value.id))+'">View Details</a></td>' +
                                '<td>'+value.mgmts+'</td>'+
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
                $('#mCadRequestList').append(PageContent);
            }
        }
    }
}*/


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
                fnSearchCadIndent();
            }
        }
    }
}

$('#mCadIndentList').on('click', 'th.sortable', function () {
    var ReturnVal							    = commonTableSorting(this);
    GlbSortOrder	  							= ReturnVal[1];
    GlbColumnId									= ReturnVal[0];
    GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    console.log(GlbSearchParam);
    MakePostRequest(base_path+'dashboard/indentlist',GlbSearchParam,'json',fnListCadIndentRes);
});
$('#btnChangeStatus').on('click',function () {
    var StatusOptSelVal                         = $('#frmItemStatus').val();
    if(parseInt(StatusOptSelVal) > 0) {
        var ArrItemCheckBoxSel                  = commonCheckbox();
        var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
        $('#ErrItemStatus').text("");
        if(parseInt(ArrItemCheckBoxSel[1]) == 0) {$('#ErrItemStatus').html("Choose a record");}
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
        $('#ErrItemStatus').text("Choose an Option");
    }
});

/*
$('#frmBasicCutoffdateFrom').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});
*/

$('#datetimepicker3').datetimepicker({
    format: 'DD-MM-YYYY HH:mm:ss'
});
$('#datetimepicker4').datetimepicker({
    format: 'DD-MM-YYYY HH:mm:ss'
});


function fnPaginationCadIndentList(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL,Parameters,'json',fnListCadIndentRes);
}