var GlbSearchParam = ''; var GlbFilterAlpha=''; var GlbSortOrder=''; var GlbColumnId='';
var url = $(location).attr('href'); var lasturlpart = url.substr(url.lastIndexOf('/')+1);
function fnListCadRequest() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+'merchant/manageallrequest',GlbSearchParam,'json',fnListCadRequestRes);
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
function fnSearchAllReq() {
    var WipRefNo     					        = $("#frmSrchWipRefNo").val();
    var Allrequest     					        = $("#frmSrchAllreq").val();
    var BB       					            = $("#frmSrchBB").val();
    var CutOffFrom     					        = $("#frmSrchCutOffDateFrom").val();
    var CutOffTo     					        = $("#frmSrchCutOffDateTo").val();
    //var ReqDateFrom     					    = $("#frmSrchReqDateFrom").val();
    //var ReqDateTo     					        = $("#frmSrchReqDateTo").val();
    var ReqType     					        = $("#frmSrchReqType").val();
    var Requirement     					    = $("#frmSrchRequirement").val();
    var Merchant     					        = $("#frmSrchMerchantName").val();
    var CStatus     					        = $("#frmSrchCStatus").val();
    GlbFilterAlpha                              = $('#hiddenAlpha').val();
    GlbSearchParam				= "rfrom=1&wip="+WipRefNo+"&cutfrom="+CutOffFrom+"&cutto="+CutOffTo+"&req="+Requirement+"&allrequest="+Allrequest+"&cs="+CStatus+"&afilter="+
        GlbFilterAlpha+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+"&reqtype="+ReqType+"&mer="+Merchant+"&bb="+BB;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+'merchant/manageallrequest',GlbSearchParam,'json',fnListCadRequestRes);
}
function fnListCadRequestRes(data) {
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
                            if(value.requestlisttypeid == 1) {
                                var editurl = 'merchant/editcadrequest/';
                            }
                            if(value.requestlisttypeid == 2) {
                                var editurl = 'msamplerequest/addeditsamplerequest/edit/';
                            }
                            if(value.requestlisttypeid == 3) {
                                var editurl = 'mpurchase/bompurchaseRequestDetails/';
                            }
                            PageContent=PageContent+'<tr><td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>' +
                                //'<td><a href="'+base_path+'merchant/mcadrequest/editcadrequest/'+encodeURIComponent(base64_encode(value.id))+'">'+value.wip+'</a></td>' +
                                '<td><a href="'+base_path+editurl+encodeURIComponent(base64_encode(value.id))+'">'+value.wip+'</a></td>' +
                                '<td>'+value.bb+'</td>' +
                                '<td>'+value.allreq+'</td>' +
                                '<td>'+value.r+'</td>' +
                                '<td>'+value.reqtype+'</td>' +
                                '<td>'+value.reqdt+'</td>' +
                                '<td>'+value.cutoffdt+'</td>' +
                                '<td>'+value.at+'</td>' +
                                '<td>'+value.authby+'</td>' +
                                '<td><a href="javascript:void(0)"> '+value.cs+'</a></td>'+
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
}
function fnDelete(Id) {
    if(confirm("Are you want to delete this record?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+'merchant/delInfo',Parameters,'json',fnDeleteRes);
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
                fnSearchCadReq();
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
        MakePostRequest(base_path+'merchant/manageallrequest',GlbSearchParam,'json',fnListRes);
    });
/*
$('#btnChangeStatus').on('click',function () {
    var dropdownOpt                                 = $('#frmItemStatus').val();
    if(dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        $('#ErrItemStatus').html("");
        if(checkBoxLength == 0) {
            $('#ErrItemStatus').html("Choose a bill of material source");
        }
        if (checkBoxLength >= 1) {
            $('#ErrItemStatus').html("");
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
        $('#ErrItemStatus').html("Select a Option");
    }
});
*/
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
                    MakeAsynPostRequest(base_path+'merchant/changeActiveInactiveStatus',"cs=" + StatusOptSelVal + "&id=" + JSON.stringify(ObjChkSelVal),'json',fnChangeStatusRes);
                }
            }
        } else {
            $('#ErrItemStatus').html("Select a Option");
        }
    });
/*
$('#frmBasicCutoffdateFrom').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});
*/
    $(function () {
        $('#datetimepicker1').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
        $('#datetimepicker2').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
        $('#datetimepicker3').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
        $('#datetimepicker4').datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
    });
/*$('#frmSrchCutOffDateFrom').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});
$('#frmSrchCutOffDateTo').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});*/
/*$('#frmSrchReqDateFrom').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

$('#frmSrchReqDateTo').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});*/
function fnPaginationCadReq(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL,Parameters,'json',fnListCadRequestRes);
}