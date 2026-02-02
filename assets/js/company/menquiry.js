var GlbSearchParam='';
 var GlbSortOrder=''; var GlbColumnId='';
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

function fnSearchEn() {
    var frmSrchEntype = $("#frmNameSearchEnType").val();
    var frmNameSearchReqDtFrom = $("#frmNameSearchReqDtFrom").val();
    var frmNameSearchReqDtTo = $("#frmNameSearchReqDtTo").val();
    var frmSrchBb = $("#frmNameSearchBB").val();
    var frmSrchIsrior = $("#frmNameSearchSR").val();
    var frmSrchMercn = $("#frmNameSearchMName").val();
    var frmSrchStyleref = $("#frmNameSearchStyleRef").val();

    var Status = $("#frmSrchStatus").val();

    GlbSearchParam = "rfrom=1&entype=" + frmSrchEntype+"&styleref=" + frmSrchStyleref + "&isrior=" + frmSrchIsrior
        + "&bb=" + frmSrchBb + "&mercn=" + frmSrchMercn + "&reqfrom=" +frmNameSearchReqDtFrom+ "&reqto="+frmNameSearchReqDtTo+"&s="+Status;

    MakePostRequest(base_path + GlbCompanyFdr + 'menquiry/manageenquiry', GlbSearchParam, 'json', fnListRes);
}

function fnEnList(usertype) {
    GlbSearchParam = "rfrom=1";
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path + GlbCompanyFdr + 'menquiry/manageenquiry', GlbSearchParam+"&ut="+usertype, 'json', fnListRes);
}

function fnListRes(data) {
    console.log(data,'data');
    if (data != '') {
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
                            PageContent=PageContent+'<tr><td><input type="checkbox" id="'+value.id+'" class="allcbox"></td>'+
                                '<td><a href="'+base_path+GlbCompanyFdr+'menquiry/entry/'+encodeURIComponent(base64_encode(value.id))+'">'+value.enqt+'</a></td>' +
                                '<td>'+value.isrior+'</td>' +
                                '<td>'+value.drequested+'</td>' +
                                '<td>'+value.styref+'</td>' +
                                '<td>'+value.bb+'</td>' +
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
var GlbPageUrl = '';
function fnPaginationEnList(VarURL) {
    var Parameters = GlbSearchParam;
    GlbPageUrl = VarURL;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL, Parameters, 'json', fnListRes);
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
    GlbSearchParam = GlbSearchParam+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;
    if(GlbPageUrl == '') {

        MakePostRequest(base_path + GlbCompanyFdr + 'menquiry/manageenquiry',GlbSearchParam , 'json', fnListRes);
    }
    else {
        MakePostRequest(GlbPageUrl,GlbSearchParam , 'json', fnListRes);
    }


});



$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SelectedIdObject = commonCheckbox();
        var checkBoxLength   = SelectedIdObject[1];
        $('#ErrOption').text("");
        if (checkBoxLength == 0) {
            $('#ErractivateFabTypeOpt').text("Choose a enquiry");
        }
        if (checkBoxLength >= 1) {
            $('#ErractivateFabTypeOpt').text("");
            var idJson = JSON.stringify(SelectedIdObject[0]);

            if (dropdownOpt == '1') { //Activate
                if (confirm('Do you want to Activate this enquiry?')) {
                    var param = GlbSearchParam + "&actdeact=" + dropdownOpt + "&cid=" + idJson;
                    MakePostRequest(base_path + GlbCompanyFdr + 'menquiry/changemStatus', param, 'json', fnChangeStatusRes);
                }
            }
            else if (dropdownOpt == '2') { //Deactivate
                if (confirm('Do you want to Deactivate this enquiry?')) {
                    var param = GlbSearchParam + "&actdeact=" + dropdownOpt + "&cid=" + idJson;
                    MakePostRequest(base_path + GlbCompanyFdr + 'menquiry/changemStatus', param, 'json', fnChangeStatusRes);
                }
            }
        }
    }
    else {
        $('#ErrOption').text("Select a Option");
    }
});

$(function () {
    //Initialize Select2 Elements
    $(".selectbb").select2();
    $(".selectmerchant").select2();

});

