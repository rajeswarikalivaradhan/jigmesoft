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

function fnSamDeptQListSearch() {
    var WipRefNo     					        = $("#frmSrchWipRefNo").val();
    var IsrIorType     					                    = $("#frmNameSearchIsrIorType").val();
    var BB       					        = $("#frmSrchBB").val();
    var MerchantId     					                = $("#frmSrchMerchantName").val();
    var Requirement     					                = $("#frmSrchRequirement").val();
    var CutOffFrom     					                    = $("#frmSrchCutOffDateFrom").val();
    var CutOffTo     					                    = $("#frmSrchCutOffDateTo").val();
    var approvaltype     					                = $("#frmSrchapprovaltypeType").val();
    var merchant     					                = $("#frmSrchMerchantName").val();
    var CStatus     					                = $("#frmSrchCStatus").val();
    GlbFilterAlpha                                      = $('#hiddenAlpha').val();
    GlbSearchParam				= "rfrom=1&wip="+WipRefNo+"&cutfrom="+CutOffFrom+"&cutto="+CutOffTo+"&IsrIorType="+IsrIorType+"&mer="+MerchantId+"&req="+Requirement+
        "&cs=" + CStatus + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder + "&at=" + approvaltype;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+'samplinguser/samplequeuelist',GlbSearchParam,'json',fnSamDeptQListRes);
}

function fnSamDeptQList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path+'samplinguser/samplequeuelist',GlbSearchParam,'json',fnSamDeptQListRes);
}

function fnSamDeptQListRes(data) {
    console.log(data,'data');
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
                            PageContent=PageContent+'<tr><td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>' +
                                '<td>'+value.wip+'</td>' +
                                '<td>'+value.bb+'</td>' +
                                '<td><a href="'+base_path+'samplinguser/queuelistdetail/'+encodeURIComponent(base64_encode(value.id))+'">'+value.qno+'</a></td>' +
                                '<td>'+value.r+'</td>' +
                                '<td>'+value.reqdt+'</td>' +
                                '<td>'+value.cutoff+'</td>' +
                                '<td>'+value.at+'</td>' +
                                '<td>'+value.authby+'</td>' +
                                '<td>'+value.mer+'</td>' +
                                '<td><a href="javascript:void(0)">'+value.currentstatus+'</a></td>' +
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
                $('#mSamQueueList').append(PageContent);
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
                fnSamDeptQList();
            }
        }
    }
}

$('#mSamQueueList').on('click', 'th.sortable', function () {
    var ReturnVal							    = commonTableSorting(this);
    GlbSortOrder	  							= ReturnVal[1];
    GlbColumnId									= ReturnVal[0];
    GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    console.log(GlbSearchParam);
    MakePostRequest(base_path+'merchant/cadqueuelist',GlbSearchParam,'json',fnListRes);
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
                    MakePostRequest(base_path+'merchant/cadqueuelist',GlbSearchParam,'json',fnChangeStatusRes);
                }
            }
            else if (dropdownOpt == '2') { //Deactivate
                if(confirm('Do you want to Deactivate this bill of material source?')) {
                    GlbSearchParam							    = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                    MakePostRequest(base_path+'merchant/cadqueuelist',GlbSearchParam,'json',fnChangeStatusRes);
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

function fnPaginationSamDeptQueueList(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL,Parameters,'json',fnSamDeptQListRes);
}