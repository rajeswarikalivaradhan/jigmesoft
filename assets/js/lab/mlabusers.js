function fnSearch() {
    var frmSrchName = $("#frmSrchName").val();
    var frmSrchMobile = $("#frmSrchMobile").val();
    var frmSrchDesgn = $("#frmSrchDesgn").val();
    var Status = $("#frmSrchStatus").val();
    GlbSearchParam = "rfrom=1&n="+frmSrchName+"&m="+frmSrchMobile+"&d="+frmSrchDesgn+"&s=" + Status;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlabuser/manage', GlbSearchParam, 'json', fnListRes);
}
function fnList() {
    GlbSearchParam = "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlabuser/manage', GlbSearchParam, 'json', fnListRes);
}
function fnListRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent = '';
                if (data.cn > 0) {
                    ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                    if (data.ct > 0) {
                        $.each(data.re, function (index, value) {
                            PageContent = PageContent + '<tr><td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td>' +
                                '<a href="' + base_path + GlbCompanyFdr + 'mlabuser/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/">' + value.n + '' +
                                '</a>' +
                                '</td>' +
                                '<td>' + value.ds + '</td>' +
                                '<td>' + value.e + '</td>' +
                                '<td>' + value.m + '</td>' +
                                '<td>' + value.s + '</td>' +
                                '<td>' + value.ub + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="'+base_path+GlbCompanyFdr+'mlabuser/addedit/'+encodeURIComponent(base64_encode(value.id))+'/edit'+'">' +
                                '<i class="fa fa-edit"></i>&nbsp;Edit</a>' +
                                '</td>';
                            PageContent = PageContent + '</tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent = PageContent + '<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if (data.pa != undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $('tbody').empty();
                $('#tableId').append(PageContent);
            }
        }
    }
}
function fnPaginationLabUser(VarURL) {
    $("#DivTotalCntResult").text('');
    MakeAsynPostRequest(VarURL, GlbSearchParam, 'json', fnListRes);
}
$('#tableId').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);
    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    GlbSearchParam = GlbSearchParam+"&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlabuser/manage', GlbSearchParam, 'json', fnListRes);
});
function fnChangeStatusRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearch();
            }
        }
    }
}
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            alert("Select User");
        }
        if (checkBoxLength >= 1) {
            var companyid_json = JSON.stringify(cboxObj);
            if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
                GlbSearchParam = "rfrom=1&actDeact=" + dropdownOpt + "&cid=" + companyid_json;
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlabuser/changeStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            }
        }
    }
    else {
        alert('Select either '+GlbStatusForMaster['1']+' or '+GlbStatusForMaster['2']);
    }
});