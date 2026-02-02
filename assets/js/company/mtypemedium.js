function fnSave() {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var Yarn = $("#frmBasicYarn").val();
    var Status = $("#frmBasicStatus").val();

    if (jsTrim(Yarn) == "") {
        $('#ErrfrmBasicYarn').text("Please fill Type Medium");
        $('#frmBasicYarn').focus();
        $('#frmBasicYarn').css("border", "1px solid #B94A48");
        return false;
    }
    if (Status == "") {
        $('#ErrBasicStatus').text("Please select the status");
        $('#frmBasicStatus').focus();
        $('#frmBasicStatus').css("border", "1px solid #B94A48");
        return false;
    }
    MakeAsynPostRequest(base_path + GlbCompanyFdr + "mtypemedium/updateInfo", "rfrom=1&y=" + Yarn + "&s=" + Status + "&id=" + GlbId, "json", function (data) {
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == -1) {
                $('#AnyErrElse').text(data.msg);
                return false;
            } else if (data.errcode == 1) {
                GlbId = data.id;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Updated successfully");
                fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mtypemedium/addedit/' + data.eid);
            }
        }
    });
}

function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mtypemedium/manage', GlbSearchParam, 'json', fnListRes);
}

function fnListRes(data) {
    console.log(data.re, 'data');
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent = '';
                if (data.cn > 0) {
                    console.log(data, 'data');
                    ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                    if (data.ct > 0) {
                        $.each(data.re, function (index, value) {
                            PageContent = PageContent + '<tr>' +
                                '<td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mtypemedium/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/">' + value.y + '</a></td>' +
                                '<td>' + value.s + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mtypemedium/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">' +
                                '<i class="fa fa-edit"></i>&nbsp;Edit</a></td>';
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


var GlbSortOrder = '';
var GlbColumnId = '';

function fnSearch() {
    var frmSrchYarn = $("#frmSrchYarn").val();
    var Status = $("#frmSrchStatus").val();
    GlbSearchParam = "rfrom=1&y=" + frmSrchYarn + "&s=" + Status;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mtypemedium/manage', GlbSearchParam, 'json', fnListRes);
}

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

$('#tableId').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);
    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    var Param = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mtypemedium/manage', Param, 'json', fnListRes);

});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            alert("Select Yarn count");
        }
        if (checkBoxLength >= 1) {
            var companyid_json = JSON.stringify(cboxObj);
            if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
                var Param = "rfrom=1&actDeact=" + dropdownOpt + "&cid=" + companyid_json;
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mtypemedium/changemStatus', Param, 'json', fnChangeStatusRes);
            }
        }
    }
    else {
        alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
    }
});

function fnPaginationYarnCount(VarURL) {
    $("#DivTotalCntResult").text('');
    var Param = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakeAsynPostRequest(VarURL, Param, 'json', fnListRes);
}