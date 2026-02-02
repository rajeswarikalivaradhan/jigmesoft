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
    var Pirefno     					                = $("#frmSrchPirefno").val();
    var Requirement     					                = $("#frmSrchRequirement").val();
    var Merchant     					                = $("#frmSrchMerchantName").val();
    var cutfrom     					                    = $("#frmSrchCutOffDateFrom").val();
    var CutOffTo     					                    = $("#frmSrchCutOffDateTo").val();
    var CStatus     					                = $("#frmSrchCStatus").val();
    var Vendor     					                = $("#frmSrchVendor").val();
    GlbFilterAlpha                                      = $('#hiddenAlpha').val();
    GlbFilterAlpha                                      = $('#hiddenAlpha').val();
    /*
        $clickedColumnId = xssclean($this->input->post('columnId'));
        $newsortorder    = xssclean($this->input->post('sortorder'));
        $VarAfilter      = xssclean($this->input->post('afilter'));
    */
    GlbSearchParam = "rfrom=1&wip="+WipRefNo+"&IsrIorType="+IsrIorType+"&bb="+BB+"&pirefno="+Pirefno+"&req="+Requirement+"&mer="+Merchant+"&cutfrom="+cutfrom+
        "&cutto="+CutOffTo+"&ven="+Vendor+"&cs="+CStatus;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+'dashboard/bompurchaseindentlist',GlbSearchParam,'json',fnQListRes);
}
function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+'dashboard/bompurchaseindentlist',GlbSearchParam,'json',fnQListRes);
}
function fnQListRes(data) {
    console.log(data,'data');
    if(data!='') {
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent=''; var pinourl = '';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Record(s) : '+data.cn+'</div>';
                    if(data.ct>0) {
                        $.each(data.re,function(index,value) {
                            console.log(GlbUt,'GlbUt');
                            if(GlbUt == 8) {
                                pinourl = base_path+'purchaseuser/bomPurchasePayemntReq/'+value.id+'/'+value.rid;
                            }
                            else if(GlbUt == 9) {
                                pinourl = base_path+'storesuser/bomitem_received_details/'+value.rid+'/'+value.pirefno_alone;
                            }
                            PageContent=PageContent+'<tr><td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>' +
                                '<td><a href="'+base_path+'purchaseuser/bomPurchaseIndentInvoiceView/'+value.id+'/'+value.rid+'">'+value.wip+'</a></td>' +
                                '<td>'+value.bb+'</td>' +
                                //'<td><a href="'+pinourl+'">'+value.pirefno+'</a></td>' +
                                '<td><div class="dropdown">\n' +
                                '                    <a href="#" class="dropbtn">' + value.pirefno + '</a>\n' +
                                '                    <div class="dropdown-content">\n' +
                                '                        <a href="' + pinourl + '" target="_blank">BOM Item Received Details</a>\n' +
                                '                        <a href="' + base_path + 'storesuser/newbomstocklist/' + value.id + '/'+value.oid+'" target="_blank">New BOM Stock List</a>\n' +
                                '                    </div>\n' +
                                '                </div>' +
                                '</td>' +
                                '<td>'+value.r+'</td>' +
                                '<td>'+value.ven+'</td>' +
                                '<td>'+value.exptdelidt+'</td>' +
                                '<td>'+value.cutoff+'</td>' +
                                '<td>'+value.appd+'</td>' +
                                '<td>'+value.mer+'</td>' +
                                '<td><a href="javascript:void(0)">'+value.cs+'</a></td>' +
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
                $('#mBomPurchaseIndentList').append(PageContent);
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
$('#mBomPurchaseIndentList').on('click', 'th.sortable', function () {
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