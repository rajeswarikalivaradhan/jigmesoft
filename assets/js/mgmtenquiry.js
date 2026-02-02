var GlbSearchParam='';
var GlbFilterAlpha=''; var GlbSortOrder=''; var GlbColumnId='';
$('#frmNameSearchReqDtFrom').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

$('#frmNameSearchReqDtTo').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

/*$('#frmNameSearchFromPo').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true
});*/

/*$('#frmNameSearchToPo').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true
});*/

function fnSearchEn() {
    var frmSrchEntype = $("#frmNameSearchEnType").val();
    var frmNameSearchReqDtFrom = $("#frmNameSearchReqDtFrom").val();
    var frmNameSearchReqDtTo = $("#frmNameSearchReqDtTo").val();
    var frmSrchBb = $("#frmNameSearchBB").val();
    var frmSrchIsrior = $("#frmNameSearchSR").val();
    var frmSrchMercn = $("#frmNameSearchMName").val();
    var frmSrchStyleref = $("#frmNameSearchStyleRef").val();
    /*
        var frmSrchPoto = $("#frmNameSearchToPo").val();
        var frmSrchPofrom = $("#frmNameSearchFromPo").val();
    */

    var Status = $("#frmSrchStatus").val();

    GlbSearchParam = "rfrom=1&entype=" + frmSrchEntype +
        /*"&pofrom=" + frmSrchPofrom + "&poto=" + frmSrchPoto +*/
        "&styleref=" + frmSrchStyleref + "&isrior=" + frmSrchIsrior
        + "&bb=" + frmSrchBb + "&mercn=" + frmSrchMercn + "&reqfrom=" +frmNameSearchReqDtFrom+ "&reqto="+frmNameSearchReqDtTo+"&s="+Status;

    MakePostRequest(base_path + 'management/mgmtenquiry', GlbSearchParam, 'json', fnListRes);
}

function fnEnList() {
    GlbSearchParam = "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path + 'management/mgmtenquiry', GlbSearchParam, 'json', fnListRes);
}

function fnListRes(data) {
    if (data != '') {
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            }
            else if(data.errcode == '-1') {
                alert(data.msg);
            }
            else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Record(s) : '+data.cn+'</div>';
                    if(data.ct>0) {
                        //var bg = '';
                        $.each(data.re,function(index,value) {
                            if(value.resend == '1') {
                                //bg = 'style="background-color: #aeeaae"';
                            }
                            //else bg = '';
                            PageContent=PageContent+'<tr><td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>'+
                                '<td>'+value.orderenqrefno+'</td>' +
                                '<td>'+value.drequested+'</td>' +
                                '<td>'+value.bb+'</td>' +
                                '<td><a href="'+base_path+"management/enquiryview/"+encodeURIComponent(base64_encode(value.id))+'">'+value.isrior+'</a></td>' +
                                '<td>'+value.enqt+'</td>' +
                                '<td>'+value.styref+'</td>' +
                                '<td>'+value.cfmpr+'</td>' +
                                '<td>'+value.curr+'</td>' +
                                '<td>'+value.mname+'</td>' +
                                '<td>'+value.ano+'</td>' +
                                '<td>'+value.dateauth+'</td>' +
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
                $('#enListTbl').append(PageContent);
            }
        }
    }
}

function fnPaginationEnList(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL, Parameters, 'json', fnListRes);
}

function fnDeleteEnquiry(Id) {
    if (confirm("Are you want to delete this enquiry?")) {
        var Parameters = "id=" + Id;
        MakePostRequest(base_path + 'management/delEnquiryInfo', Parameters, 'json', fnDeleteRes);
    }
}
function fnDeleteRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnEnList();
            }
        }
    }
}

function fnChangeStatusRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnEnList();
            }
        }
    }
}

$('#enListTbl').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);


    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];

    var Parameters = GlbSearchParam+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;
    MakePostRequest(base_path + 'management/mgmtenquiry',Parameters , 'json', fnListRes);

});

$("span[class^='alpha_']").on('click', function () {
    fnFilterBGColor(this);
    //alert('one');

    if ($(this).hasClass('alpha_all_filter')) { //All accounts
        //alert('two');
        fnEnList();

    }
    else {
        GlbFilterAlpha = $(this).text();

        var param = GlbSearchParam + "&afilter=" + GlbFilterAlpha;
        MakePostRequest(base_path + 'management/mgmtenquiry', param, 'json', fnListRes);
    }

});

$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var checkboxRe = commonCheckbox();
        var checkBoxLength = checkboxRe[1];
        var cboxObj = checkboxRe[0];
        $('#ErrOption').html("");
        if (checkBoxLength == 0) {
            $('#ErractivateFabTypeOpt').html("Choose a enquiry");
        }
        if (checkBoxLength >= 1) {
            $('#ErractivateFabTypeOpt').html("");
            var cid = JSON.stringify(cboxObj);
            if (dropdownOpt == '1') { //Activate
                if (confirm('Do you want to activate this enquiry?')) {
                    var param = GlbSearchParam + "&actdeact=" + dropdownOpt + "&cid=" + cid;
                    MakePostRequest(base_path + 'management/changemStatus', param, 'json', fnChangeStatusRes);
                }
            }
            else if (dropdownOpt == '2') { //Deactivate
                if (confirm('Do you want to Deactivate this enquiry?')) {
                    var param = GlbSearchParam + "&actdeact=" + dropdownOpt + "&cid=" + cid;
                    MakePostRequest(base_path + 'management/changemStatus', param, 'json', fnChangeStatusRes);
                }
            }
        }
    }
    else {
        $('#ErrOption').html("Select a Option");
    }
});

$(function () {
    //Initialize Select2 Elements
    $(".selectbb").select2();
    $(".selectmerchant").select2();

});