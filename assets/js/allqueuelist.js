var GlbSearchParam = ''; var GlbFilterAlpha=''; var GlbSortOrder=''; var GlbColumnId='';
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
function fnQListSearch() {
    var WipRefNo     					        = $("#frmSrchWipRefNo").val();
    var IsrIorType     					                    = $("#frmNameSearchIsrIorType").val();
    var BB       					        = $("#frmSrchBB").val();
    var AllReq     					                = $("#frmSrchAllReq").val();
    var Requirement     					                = $("#frmSrchRequirement").val();
    var CutOffFrom     					                    = $("#frmSrchCutOffDateFrom").val();
    var CutOffTo     					                    = $("#frmSrchCutOffDateTo").val();
    var approvaltype     					                = $("#frmSrchapprovaltypeType").val();
    var merchant     					                = $("#frmSrchMerchantName").val();
    var CStatus     					                = $("#frmSrchCStatus").val();
    GlbFilterAlpha                                      = $('#hiddenAlpha').val();
    GlbSearchParam				= "rfrom=1&wip="+WipRefNo+"&cutfrom="+CutOffFrom+"&cutto="+CutOffTo+"&IsrIorType="+IsrIorType+"&bb="+BB+"&allreq="+AllReq+"&req="+Requirement+
        "&cs=" + CStatus + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder + "&at=" + approvaltype + "&mer=" + merchant;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+'dashboard/allqueuelist',GlbSearchParam,'json',fnQListRes);
}

function fnQList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+'dashboard/allqueuelist',GlbSearchParam,'json',fnQListRes);
}
function fnQListRes(data) {
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
                        if(GlbUt == 3) {
                            $.each(data.re,function(index,value) {
                                /*<div class="dropdown">\n' +
'                    <a href="#" class="dropbtn">' + value.bb + '</a>\n' +
'                    <div class="dropdown-content">\n' +
'                        <a href="' + base_path + 'orderentryvtwo/entry/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">Order Entry</a>\n' +
'                        <a href="' + base_path + 'fabricprogramvtwo/fabricdetail/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">Fabric Program</a>\n' +
'                        <a href="' + base_path + 'merchant/addcadrequest/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">CAD Request</a>\n' +
'                        <a href="' + base_path +'msamplerequest/addeditsamplerequest/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">Sample Request</a>\n' +
'                        <a href="' + base_path +GlbCompanyFdr+'mpurchase/addeditbompurchase/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">BOM Request</a>\n' +
'                    </div>\n' +
'                </div>' +*/

                                if(value.reqlisttypeid == 3) {
                                    var qnourl = base_path+'dashboard/mgmtbompurchaseindentapprovalreq/'+value.id;
                                }
                                else {
                                    //var qnourl = base_path+'dashboard/allqueuelistdetail/'+encodeURIComponent(base64_encode(value.id));
                                    var qnourl = base_path+'dashboard/allqueuelistdetail/'+value.id;
                                }
                                PageContent=PageContent+'<tr><td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>' +
                                    '<td>'+value.wip+'</td>' +
                                    '<td>'+value.bb+'</td>' +
                                    '<td>'+
                                    '<div class="dropdown">' +
                                    '<a href="#" class="dropbtn">'+value.qno+'</a>' +
                                    '<div class="dropdown-content">' +
                                    '<a href="'+qnourl+'">Mgmt Approval</a> ' +
                                    '</div>' +
                                    '</div>' +
                                    '</td>' +
                                    '<td>'+value.allreq+'</td>' +
                                    '<td>'+value.r+'</td>' +
                                    '<td>'+value.requestdt+'</td>' +
                                    '<td>'+value.cutoff+'</td>' +
                                    '<td>'+value.at+'</td>' +
                                    '<td>'+value.mer+'</td>' +
                                    '<td><a href="javascript:void(0)">'+value.cads+'</a></td>' +
                                    '<td>'+value.ru+'</td>'+
                                    '<td>'+value.s+'</td>';
                                PageContent=PageContent+'</tr>';
                            });
                        }
                        else if(GlbUt == 4) {
                            $.each(data.re,function(index,value) {
                                PageContent = PageContent + '<tr><td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                    '<td>'+value.wip+'</td>' +
                                    '<td>'+value.bb+'</td>' +
                                    '<td><a href="'+base_path+'dashboard/allqueuelistdetail/'+encodeURIComponent(base64_encode(value.id))+'">'+value.qno+'</a></td>' +
                                    '<td>' + value.r + '</td>' +
                                    '<td>'+value.requestdt+'</td>' +
                                    '<td>' + value.cutoff + '</td>' +
                                    '<td>' + value.at + '</td>' +
                                    '<td>' + value.mgmt + '</td>' +
                                    '<td><a href="javascript:void(0)">' + value.cads + '</a></td>' +
                                    '<td>' + value.ru + '</td>' +
                                    '<td>' + value.s + '</td>';
                                PageContent = PageContent + '</tr>';
                            });
                        }
                        else if(GlbUt == 8) {
                            $.each(data.re,function(index,value) {
                                PageContent = PageContent + '<tr><td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                    '<td>'+value.wip+'</td>' +
                                    '<td>'+value.bb+'</td>' +
                                    '<td><a href="'+base_path+'purchaseuser/bompurchaseindentapproval/'+value.id+'">'+value.qno+'</a></td>' +
                                    '<td>' + value.r + '</td>' +
                                    '<td>'+value.requestdt+'</td>' +
                                    '<td>' + value.cutoff + '</td>' +
                                    '<td>' + value.at + '</td>' +
                                    '<td>' + value.mgmt + '</td>' +
                                    '<td>' + value.mer + '</td>' +
                                    '<td><a href="javascript:void(0)">' + value.cads + '</a></td>' +
                                    '<td>' + value.ru + '</td>' +
                                    '<td>' + value.s + '</td>';
                                PageContent = PageContent + '</tr>';
                            });
                        }
                        else if(GlbUt == 9) {
                            $.each(data.re,function(index,value) {
                                PageContent = PageContent + '<tr><td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                    '<td>'+value.wip+'</td>' +
                                    '<td>'+value.bb+'</td>' +
                                    '<td><a href="'+base_path+'dashboard/storesBompurchaseIndentAppr/'+encodeURIComponent(base64_encode(value.id))+'">'+value.qno+'</a></td>' +
                                    '<td>' + value.r + '</td>' +
                                    '<td>'+value.requestdt+'</td>' +
                                    '<td>' + value.cutoff + '</td>' +
                                    '<td>' + value.at + '</td>' +
                                    '<td>' + value.mgmt + '</td>' +
                                    '<td>' + value.mer + '</td>' +
                                    '<td><a href="javascript:void(0)">' + value.cads + '</a></td>' +
                                    '<td>' + value.ru + '</td>' +
                                    '<td>' + value.s + '</td>';
                                PageContent = PageContent + '</tr>';
                            });
                        }
                        else {
                            $.each(data.re,function(index,value) {
                                PageContent = PageContent + '<tr><td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                    '<td>'+value.wip+'</td>' +
                                    '<td>'+value.bb+'</td>' +
                                    '<td><a href="'+base_path+'dashboard/allqueuelistdetail/'+encodeURIComponent(base64_encode(value.id))+'">'+value.qno+'</a></td>' +
                                    '<td>' + value.allreq + '</td>' +
                                    '<td>' + value.r + '</td>' +
                                    '<td>'+value.requestdt+'</td>' +
                                    '<td>' + value.cutoff + '</td>' +
                                    '<td>' + value.at + '</td>' +
                                    '<td>' + value.mgmt + '</td>' +
                                    '<td><a href="javascript:void(0)">' + value.cads + '</a></td>' +
                                    '<td>' + value.ru + '</td>' +
                                    '<td>' + value.s + '</td>';
                                PageContent = PageContent + '</tr>';
                            });
                        }
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
                $('#mCadQueueList').append(PageContent);
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
                fnQList();
            }
        }
    }
}
$('#mCadQueueList').on('click', 'th.sortable', function () {
    var ReturnVal							    = commonTableSorting(this);

    GlbSortOrder	  							= ReturnVal[1];
    GlbColumnId									= ReturnVal[0];
    GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    console.log(GlbSearchParam);
    MakePostRequest(base_path+'dashboard/allqueuelist',GlbSearchParam,'json',fnListRes);
});
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
                    MakePostRequest(base_path+'dashboard/allqueuelistChangeStatus',GlbSearchParam,'json',fnChangeStatusRes);
                }
            }
            else if (dropdownOpt == '2') { //Deactivate
                if(confirm('Do you want to Deactivate this bill of material source?')) {
                    GlbSearchParam							    = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                    MakePostRequest(base_path+'dashboard/allqueuelistChangeStatus',GlbSearchParam,'json',fnChangeStatusRes);
                }
            }
        }
    }
    else {
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
function fnPaginationAllQList(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL,Parameters,'json',fnQListRes);
}
$(document).ajaxStart(function(a){
    $.LoadingOverlay("show",{image: base_path+"assets/img/fullpage.gif"});
});
$(document).ajaxStop(function(){
    $.LoadingOverlay("hide");
});